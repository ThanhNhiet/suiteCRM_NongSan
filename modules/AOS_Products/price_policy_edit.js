/**
 * JavaScript for Price Policy dynamic rows in Product EditView
 * Similar to Line Items in AOS_Quotes/AOS_Invoices
 */

var pricePolicyLineCount = 0;

/**
 * Initialize Price Policy table
 */
function initPricePolicyTable() {
    var html = '<table id="pricePolicyTable" border="0" width="100%" cellpadding="0" cellspacing="0" style="margin-top:10px;">';
    html += '<thead>';
    html += '<tr style="background-color: #f5f5f5;">';
    html += '<th style="padding: 5px; text-align: center; width: 5%;">#</th>';
    html += '<th style="padding: 5px; text-align: left; width: 35%;">Khách hàng / Đối tác <span style="color:red;">*</span></th>';
    html += '<th style="padding: 5px; text-align: left; width: 25%;">Giá <span style="color:red;">*</span></th>';
    html += '<th style="padding: 5px; text-align: left; width: 20%;">Mệnh giá</th>';
    html += '<th style="padding: 5px; text-align: center; width: 10%;">Xóa</th>';
    html += '</tr>';
    html += '</thead>';
    html += '<tbody id="pricePolicyTableBody">';
    html += '</tbody>';
    html += '</table>';
    html += '<div style="margin-top: 10px;">';
    html += '<input type="button" class="button" value="Thêm Chính sách giá" onclick="addPricePolicyLine()" />';
    html += '</div>';
    
    return html;
}

/**
 * Add a new Price Policy line
 */
function addPricePolicyLine(policyId, accountId, accountName, price, currencyId) {
    // Get actual number of existing rows instead of using global counter
    var tbody = document.getElementById('pricePolicyTableBody');
    var existingRows = tbody ? tbody.getElementsByTagName('tr').length : 0;
    pricePolicyLineCount = existingRows + 1;
    var lineNum = pricePolicyLineCount;
    
    // Set default values
    policyId = policyId || '';
    accountId = accountId || '';
    accountName = accountName || '';
    price = price || '';
    currencyId = currencyId || '-99';
    
    var html = '<tr id="pricePolicyLine' + lineNum + '">';
    
    // Line number
    html += '<td style="padding: 5px; text-align: center; vertical-align: middle;">' + lineNum + '</td>';
    
    html += '<td style="padding: 5px; position: relative;">';
    html += '<input type="hidden" name="pp_id[]" id="pp_id_' + lineNum + '" value="' + policyId + '">';
    html += '<input type="hidden" name="pp_account_id[]" id="pp_account_id_' + lineNum + '" value="' + accountId + '">';
    
    html += '<div style="display: table; width: 100%;">';
    html += '<div style="display: table-cell; width: 100%;">';
    html += '<input type="text" name="pp_account_name[]" id="pp_account_name_' + lineNum + '" value="' + accountName + '" ';
    html += 'class="sqsEnabled" autocomplete="off" ';
    html += 'style="width: 100%;" ';
    html += 'onfocus="showAccountDropdown(' + lineNum + ')" ';
    html += 'onblur="hideAccountDropdown(' + lineNum + ')" ';
    html += 'onkeyup="searchAccounts(' + lineNum + ', this.value)" />';
    html += '</div>';
    html += '<div style="display: table-cell; white-space: nowrap; padding-left: 3px;">';
    html += '<button type="button" name="btn_pp_account_' + lineNum + '" id="btn_pp_account_' + lineNum + '" ';
    html += 'tabindex="0" title="Chọn" class="button" ';
    html += 'style="padding: 2px 5px; min-width: 30px; min-height: 20px;" ';
    html += 'onclick="openAccountPopup(' + lineNum + ');">';
    html += '<img src="themes/default/images/id-ff-select.png" alt="Chọn" width="20" height="20" align="absmiddle" border="0">';
    html += '</button>';
    html += '</div>';
    html += '</div>';
    
    html += '<div id="pp_account_results_' + lineNum + '" class="ac-results" style="position:absolute; z-index:9999; display:none; background:#fff; border:1px solid #aaa; box-shadow: 0 2px 4px rgba(0,0,0,0.2); max-height:200px; overflow-y:auto; width: calc(100% - 10px); margin-top: 1px;"></div>';
    html += '</td>';
    
    // Price field
    html += '<td style="padding: 5px;">';
    html += '<input type="text" name="pp_price[]" id="pp_price_' + lineNum + '" value="' + price + '" size="20" style="text-align: left; width: 90%;" />';
    html += '</td>';
    
    // Currency dropdown
    html += '<td style="padding: 5px;">';
    html += '<select name="pp_currency_id[]" id="pp_currency_id_' + lineNum + '" style="width: 95%;">';
    if (typeof PRICE_POLICY_CURRENCIES !== 'undefined' && PRICE_POLICY_CURRENCIES.length > 0) {
        for (var i = 0; i < PRICE_POLICY_CURRENCIES.length; i++) {
            var curr = PRICE_POLICY_CURRENCIES[i];
            var selected = (curr.id == currencyId) ? ' selected="selected"' : '';
            html += '<option value="' + curr.id + '"' + selected + '>' + curr.name + '</option>';
        }
    } else {
        console.warn('PRICE_POLICY_CURRENCIES not defined or empty, using fallback');
        html += '<option value="-99" selected="selected">US Dollar</option>';
    }
    html += '</select>';
    html += '</td>';
    
    // Delete button
    html += '<td style="padding: 5px; text-align: center;">';
    html += '<button type="button" class="button" onclick="deletePricePolicyLine(' + lineNum + ')" style="background-color: #dc3545; color: white;">XÓA</button>';
    html += '</td>';
    
    html += '</tr>';
    
    // Use insertAdjacentHTML to prevent losing existing input values
    document.getElementById('pricePolicyTableBody').insertAdjacentHTML('beforeend', html);
}

/**
 * Delete a Price Policy line
 */
function deletePricePolicyLine(lineNum) {
    var row = document.getElementById('pricePolicyLine' + lineNum);
    if (row) {
        row.parentNode.removeChild(row);
        renumberPricePolicyLines();
        // Update line count to actual number of rows
        var tbody = document.getElementById('pricePolicyTableBody');
        pricePolicyLineCount = tbody ? tbody.getElementsByTagName('tr').length : 0;
    }
}

/**
 * Renumber all lines after deletion
 */
function renumberPricePolicyLines() {
    var tbody = document.getElementById('pricePolicyTableBody');
    var rows = tbody.getElementsByTagName('tr');
    
    for (var i = 0; i < rows.length; i++) {
        var firstCell = rows[i].getElementsByTagName('td')[0];
        if (firstCell) {
            firstCell.innerHTML = (i + 1);
        }
    }
}

/**
 * Search Accounts using AJAX
 */
function searchAccounts(lineNum, query) {
    if (query.length < 2) {
        document.getElementById('pp_account_results_' + lineNum).style.display = 'none';
        return;
    }
    
    // Use custom AJAX endpoint
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'index.php?entryPoint=AccountSearchAjax&search=' + encodeURIComponent(query), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                var results = JSON.parse(xhr.responseText);
                displayAccountResults(lineNum, results);
            } catch(e) {
                console.error('Failed to parse account results', e);
            }
        }
    };
    xhr.send();
}

/**
 * Display account search results
 */
function displayAccountResults(lineNum, results) {
    var resultsDiv = document.getElementById('pp_account_results_' + lineNum);
    
    if (!results || results.length === 0) {
        var html = '<div style="padding:8px; color:#666; font-style:italic;">Không tìm thấy kết quả</div>';
        resultsDiv.innerHTML = html;
        resultsDiv.style.display = 'block';
        return;
    }
    
    var html = '<ul style="list-style:none; margin:0; padding:0;">';
    for (var i = 0; i < results.length; i++) {
        html += '<li style="padding:8px 10px; cursor:pointer; border-bottom:1px solid #eee;" ';
        html += 'onmouseover="this.style.backgroundColor=\'#e8f4f8\'" ';
        html += 'onmouseout="this.style.backgroundColor=\'#fff\'" ';
        html += 'onmousedown="selectAccountFromDropdown(' + lineNum + ', \'' + results[i].id + '\', \'' + escapeHtml(results[i].name) + '\')">';
        html += '<strong>' + results[i].name + '</strong>';
        html += '</li>';
    }
    html += '</ul>';
    
    resultsDiv.innerHTML = html;
    resultsDiv.style.display = 'block';
}

/**
 * Escape HTML characters
 */
function escapeHtml(text) {
    return text.replace(/'/g, "\\'").replace(/"/g, '&quot;');
}

/**
 * Select an account from dropdown
 */
function selectAccountFromDropdown(lineNum, accountId, accountName) {
    document.getElementById('pp_account_id_' + lineNum).value = accountId;
    document.getElementById('pp_account_name_' + lineNum).value = accountName;
    hideAccountDropdown(lineNum);
}

/**
 * Show account dropdown
 */
function showAccountDropdown(lineNum) {
    var input = document.getElementById('pp_account_name_' + lineNum);
    if (input && input.value.length >= 2) {
        searchAccounts(lineNum, input.value);
    }
}

/**
 * Hide account dropdown
 */
function hideAccountDropdown(lineNum) {
    setTimeout(function() {
        var resultsDiv = document.getElementById('pp_account_results_' + lineNum);
        if (resultsDiv) {
            resultsDiv.style.display = 'none';
        }
    }, 200);
}

/**
 * Open Account popup selector
 */
function openAccountPopup(lineNum) {
    // Build popup URL with proper parameters for SuiteCRM popup
    var popupRequestData = {
        'call_back_function': 'set_pp_account_return',
        'form_name': 'EditView',
        'field_to_name_array': {
            'id': 'pp_account_id_' + lineNum,
            'name': 'pp_account_name_' + lineNum
        }
    };
    
    var encoded_request_data = JSON.stringify(popupRequestData);
    
    var popupUrl = 'index.php?module=Accounts&action=Popup&html=Popup_picker' +
                   '&form=EditView' +
                   '&form_submit=false' +
                   '&request_data=' + encodeURIComponent(encoded_request_data);
    
    // Open popup window and save reference
    window.last_popup = open_popup(
        'Accounts',           // module
        600,                  // width
        400,                  // height  
        '',                   // initial_filter
        false,                // show_tabs
        true,                 // close_on_select - AUTO CLOSE
        popupRequestData      // request_data
    );
}

/**
 * Callback function for popup - will be called by SuiteCRM popup
 */
window.set_pp_account_return = function(popupReplyData) {
    if (typeof popupReplyData != 'undefined') {
        var form_name = popupReplyData.form_name;
        var name_to_value_array = popupReplyData.name_to_value_array;
        
        for (var field_name in name_to_value_array) {
            if (name_to_value_array.hasOwnProperty(field_name)) {
                var value = name_to_value_array[field_name];
                var fieldElement = document.getElementById(field_name);
                
                if (fieldElement) {
                    fieldElement.value = value;
                }
            }
        }
    }
    
    // Close the popup - callback runs in parent window, need to close the popup window
    // Try multiple methods to close the popup
    setTimeout(function() {
        // Method 1: Close via global popup window reference
        if (typeof window.last_popup !== 'undefined' && window.last_popup && !window.last_popup.closed) {
            window.last_popup.close();
        }
        
        // Method 2: Try to find popup by name
        try {
            var popupWin = window.open('', 'sugar_popup', '');
            if (popupWin && !popupWin.closed) {
                popupWin.close();
            }
        } catch(e) {}
        
        // Method 3: Close any window with name containing 'popup'
        if (typeof SUGAR !== 'undefined' && SUGAR.util && SUGAR.util.closeActivityPanel) {
            SUGAR.util.closeActivityPanel.hide();
        }
    }, 150);
};

/**
 * Validate Price Policy data before form submission
 */
function validatePricePolicyData() {
    var tbody = document.getElementById('pricePolicyTableBody');
    var rows = tbody.getElementsByTagName('tr');
    
    for (var i = 0; i < rows.length; i++) {
        var accountInput = rows[i].querySelector('input[name="pp_account_name[]"]');
        var priceInput = rows[i].querySelector('input[name="pp_price[]"]');
        
        if (!accountInput || !priceInput) continue;
        
        // Check if account is selected (must have account_id)
        var accountIdInput = rows[i].querySelector('input[name="pp_account_id[]"]');
        if (!accountIdInput || !accountIdInput.value) {
            alert('Vui lòng chọn Khách hàng / Đối tác cho dòng ' + (i + 1));
            accountInput.focus();
            return false;
        }
        
        // Check if price is entered
        if (!priceInput.value || priceInput.value.trim() === '') {
            alert('Vui lòng nhập Giá cho dòng ' + (i + 1));
            priceInput.focus();
            return false;
        }
        
        // Validate price is a valid number
        var priceValue = parseFloat(priceInput.value.replace(/,/g, ''));
        if (isNaN(priceValue)) {
            alert('Giá không hợp lệ ở dòng ' + (i + 1));
            priceInput.focus();
            return false;
        }
    }
    
    return true;
}

// Hook into form submission
if (typeof(SUGAR) != 'undefined' && typeof(SUGAR.util) != 'undefined') {
    var originalCheck = check_form;
    check_form = function(formname) {
        if (document.getElementById('pricePolicyTableBody')) {
            if (!validatePricePolicyData()) {
                return false;
            }
        }
        return originalCheck(formname);
    };
}
