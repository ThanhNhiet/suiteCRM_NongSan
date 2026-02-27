/**
 * JavaScript for Combo Component dynamic rows in Product QuickCreate/EditView
 * Similar to Line Items in AOS_Quotes/AOS_Invoices
 */

var comboComponentLineCount = 0;

/**
 * Initialize Combo Component table
 */
function initComboComponentTable() {
    var html = '<table id="comboComponentTable" border="0" width="100%" cellpadding="0" cellspacing="0" style="margin-top:10px;">';
    html += '<thead>';
    html += '<tr style="background-color: #f5f5f5;">';
    html += '<th style="padding: 5px; text-align: center; width: 5%;">#</th>';
    html += '<th style="padding: 5px; text-align: left; width: 40%;">Sản phẩm thành phần <span style="color:red;">*</span></th>';
    html += '<th style="padding: 5px; text-align: right; width: 20%;">Giá niêm yết</th>';
    html += '<th style="padding: 5px; text-align: center; width: 15%;">Số lượng <span style="color:red;">*</span></th>';
    html += '<th style="padding: 5px; text-align: right; width: 20%;">Thành tiền</th>';
    html += '<th style="padding: 5px; text-align: center; width: 5%;">Xóa</th>';
    html += '</tr>';
    html += '</thead>';
    html += '<tbody id="comboComponentTableBody">';
    html += '</tbody>';
    html += '</table>';
    html += '<div style="margin-top: 10px;">';
    html += '<input type="button" class="button" value="Thêm sản phẩm thành phần" onclick="addComboComponentLine()" />';
    html += '</div>';
    html += '<div style="margin-top: 15px; padding: 10px; background-color: #f0f0f0; border: 1px solid #ddd;">';
    html += '<strong>Tổng cộng: </strong><span id="comboTotalPrice" style="font-size: 16px; color: #c00;">0</span>';
    html += '</div>';
    
    return html;
}

/**
 * Add a new Combo Component line
 */
function addComboComponentLine(componentId, productId, productName, standardPrice, quantity, currencyId) {
    // Get actual number of existing rows
    var tbody = document.getElementById('comboComponentTableBody');
    var existingRows = tbody ? tbody.getElementsByTagName('tr').length : 0;
    comboComponentLineCount = existingRows + 1;
    var lineNum = comboComponentLineCount;
    
    // Set default values
    componentId = componentId || '';
    productId = productId || '';
    productName = productName || '';
    standardPrice = standardPrice || '0';
    quantity = quantity || '1';
    currencyId = currencyId || getParentCurrencyId();
    
    var subtotal = parseFloat(standardPrice) * parseFloat(quantity);
    
    var html = '<tr id="comboComponentLine' + lineNum + '">';
    
    // Line number
    html += '<td style="padding: 5px; text-align: center; vertical-align: middle;">' + lineNum + '</td>';
    
    // Product field with popup selector
    html += '<td style="padding: 5px; position: relative;">';
    html += '<input type="hidden" name="cc_id[]" id="cc_id_' + lineNum + '" value="' + componentId + '">';
    html += '<input type="hidden" name="cc_product_id[]" id="cc_product_id_' + lineNum + '" value="' + productId + '">';
    html += '<input type="hidden" name="cc_currency_id[]" id="cc_currency_id_' + lineNum + '" value="' + currencyId + '">';
    
    html += '<div style="display: table; width: 100%;">';
    html += '<div style="display: table-cell; width: 100%;">';
    html += '<input type="text" name="cc_product_name[]" id="cc_product_name_' + lineNum + '" value="' + productName + '" ';
    html += 'class="sqsEnabled" autocomplete="off" style="width: 100%;" readonly />';
    html += '</div>';
    html += '<div style="display: table-cell; white-space: nowrap; padding-left: 3px;">';
    html += '<button type="button" name="btn_cc_product_' + lineNum + '" id="btn_cc_product_' + lineNum + '" ';
    html += 'tabindex="0" title="Chọn sản phẩm" class="button" ';
    html += 'style="padding: 2px 5px; min-width: 30px; min-height: 20px;" ';
    html += 'onclick="openProductPopup(' + lineNum + ');">';
    html += '<img src="themes/default/images/id-ff-select.png" alt="Chọn" width="20" height="20" align="absmiddle" border="0">';
    html += '</button>';
    html += '</div>';
    html += '</div>';
    html += '</td>';
    
    // Standard Price (readonly)
    html += '<td style="padding: 5px;">';
    html += '<input type="text" name="cc_standard_price[]" id="cc_standard_price_' + lineNum + '" value="' + formatNumber(standardPrice) + '" ';
    html += 'size="15" style="text-align: right; width: 90%; background-color: #f0f0f0;" readonly />';
    html += '</td>';
    
    // Quantity
    html += '<td style="padding: 5px;">';
    html += '<input type="text" name="cc_quantity[]" id="cc_quantity_' + lineNum + '" value="' + quantity + '" ';
    html += 'size="10" style="text-align: center; width: 70%;" ';
    html += 'onchange="calculateSubtotal(' + lineNum + ')" onkeyup="calculateSubtotal(' + lineNum + ')" />';
    html += '</td>';
    
    // Subtotal (readonly)
    html += '<td style="padding: 5px;">';
    html += '<input type="text" name="cc_subtotal[]" id="cc_subtotal_' + lineNum + '" value="' + formatNumber(subtotal) + '" ';
    html += 'size="15" style="text-align: right; width: 90%; background-color: #f0f0f0; font-weight: bold;" readonly />';
    html += '</td>';
    
    // Delete button
    html += '<td style="padding: 5px; text-align: center;">';
    html += '<button type="button" class="button" onclick="deleteComboComponentLine(' + lineNum + ')" style="background-color: #dc3545; color: white;">XÓA</button>';
    html += '</td>';
    
    html += '</tr>';
    
    document.getElementById('comboComponentTableBody').insertAdjacentHTML('beforeend', html);
    calculateTotalPrice();
}

/**
 * Delete a Combo Component line
 */
function deleteComboComponentLine(lineNum) {
    var row = document.getElementById('comboComponentLine' + lineNum);
    if (row) {
        row.parentNode.removeChild(row);
        renumberComboComponentLines();
        var tbody = document.getElementById('comboComponentTableBody');
        comboComponentLineCount = tbody ? tbody.getElementsByTagName('tr').length : 0;
        calculateTotalPrice();
    }
}

/**
 * Renumber all lines after deletion
 */
function renumberComboComponentLines() {
    var tbody = document.getElementById('comboComponentTableBody');
    var rows = tbody.getElementsByTagName('tr');
    
    for (var i = 0; i < rows.length; i++) {
        var firstCell = rows[i].getElementsByTagName('td')[0];
        if (firstCell) {
            firstCell.innerHTML = (i + 1);
        }
    }
}

/**
 * Calculate subtotal for a line
 */
function calculateSubtotal(lineNum) {
    var priceInput = document.getElementById('cc_standard_price_' + lineNum);
    var qtyInput = document.getElementById('cc_quantity_' + lineNum);
    var subtotalInput = document.getElementById('cc_subtotal_' + lineNum);
    
    if (priceInput && qtyInput && subtotalInput) {
        var price = parseFloat(priceInput.value.replace(/,/g, '')) || 0;
        var qty = parseFloat(qtyInput.value.replace(/,/g, '')) || 0;
        var subtotal = price * qty;
        
        subtotalInput.value = formatNumber(subtotal);
        calculateTotalPrice();
    }
}

/**
 * Calculate total price from all combo components
 */
function calculateTotalPrice() {
    var tbody = document.getElementById('comboComponentTableBody');
    if (!tbody) return;
    
    var rows = tbody.getElementsByTagName('tr');
    var total = 0;
    
    for (var i = 0; i < rows.length; i++) {
        var subtotalInput = rows[i].querySelector('input[name="cc_subtotal[]"]');
        if (subtotalInput) {
            var subtotal = parseFloat(subtotalInput.value.replace(/,/g, '')) || 0;
            total += subtotal;
        }
    }
    
    // Update total display
    var totalDisplay = document.getElementById('comboTotalPrice');
    if (totalDisplay) {
        totalDisplay.innerHTML = formatNumber(total);
    }
    
    // Calculate final price with discount
    calculateFinalPrice(total);
}

/**
 * Calculate final price after discount
 */
function calculateFinalPrice(totalBeforeDiscount) {
    var discountType = document.getElementById('discount_type_c');
    var discountValue = document.getElementById('discount_value_c');
    var priceField = document.getElementById('price');
    
    if (!discountType || !discountValue || !priceField) return;
    
    var discount = parseFloat(discountValue.value.replace(/,/g, '')) || 0;
    var finalPrice = totalBeforeDiscount;
    
    if (discount > 0) {
        var typeValue = discountType.value;
        // Support both English and Vietnamese values
        if (typeValue === 'Percentage' || typeValue === 'percentage' || typeValue.indexOf('Ph\u1ea7n tr\u0103m') >= 0 || typeValue.indexOf('%') >= 0) {
            finalPrice = totalBeforeDiscount * (1 - discount / 100);
        } else if (typeValue === 'Amount' || typeValue === 'amount' || typeValue.indexOf('ti\u1ec1n') >= 0 || typeValue.indexOf('\u0111\u1ecbnh') >= 0) {
            finalPrice = totalBeforeDiscount - discount;
        }
    }
    
    if (finalPrice < 0) finalPrice = 0;
    
    priceField.value = formatNumber(finalPrice);
}

/**
 * Open Product popup selector
 */
function openProductPopup(lineNum) {
    var popupRequestData = {
        'call_back_function': 'set_cc_product_return',
        'form_name': 'QuickCreate',
        'field_to_name_array': {
            'id': 'cc_product_id_' + lineNum,
            'name': 'cc_product_name_' + lineNum
        }
    };
    
    var encoded_request_data = JSON.stringify(popupRequestData);
    
    var popupUrl = 'index.php?module=AOS_Products&action=Popup&html=Popup_picker' +
                   '&form=QuickCreate' +
                   '&form_submit=false' +
                   '&request_data=' + encodeURIComponent(encoded_request_data);
    
    // Open popup window and save reference
    window.last_popup = open_popup(
        'AOS_Products',
        600,
        400,
        '',
        false,
        true,                 // close_on_select - AUTO CLOSE
        popupRequestData
    );
}

/**
 * Callback function for product popup
 */
window.set_cc_product_return = function(popupReplyData) {
    if (typeof popupReplyData != 'undefined') {
        var name_to_value_array = popupReplyData.name_to_value_array;
        
        for (var field_name in name_to_value_array) {
            if (name_to_value_array.hasOwnProperty(field_name)) {
                var value = name_to_value_array[field_name];
                var fieldElement = document.getElementById(field_name);
                
                if (fieldElement) {
                    fieldElement.value = value;
                    
                    // Get product details if this is the ID field
                    if (field_name.indexOf('cc_product_id_') === 0) {
                        var lineNum = field_name.replace('cc_product_id_', '');
                        fetchProductDetails(lineNum, value);
                    }
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
 * Fetch product details (price, currency) via AJAX
 */
function fetchProductDetails(lineNum, productId) {
    if (!productId) return;
    
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'index.php?entryPoint=ProductDetailsAjax&product_id=' + encodeURIComponent(productId), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                var product = JSON.parse(xhr.responseText);
                updateProductFields(lineNum, product);
            } catch(e) {
                console.error('Failed to parse product details', e);
            }
        }
    };
    xhr.send();
}

/**
 * Update product-related fields
 */
function updateProductFields(lineNum, product) {
    var priceField = document.getElementById('cc_standard_price_' + lineNum);
    var currencyField = document.getElementById('cc_currency_id_' + lineNum);
    
    if (priceField && product.price) {
        priceField.value = formatNumber(product.price);
    }
    
    if (currencyField && product.currency_id) {
        currencyField.value = product.currency_id;
    }
    
    calculateSubtotal(lineNum);
}

/**
 * Get parent product currency ID
 */
function getParentCurrencyId() {
    var currencyField = document.getElementById('currency_id');
    return currencyField ? currencyField.value : '-99';
}

/**
 * Format number with thousands separator
 */
function formatNumber(num) {
    var n = parseFloat(num) || 0;
    return n.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

/**
 * Toggle combo component panel based on is_combo checkbox
 */
function toggleComboComponentPanel() {
    var isComboCheckbox = document.getElementById('is_combo_c');
    var comboPanel = document.getElementById('comboComponentPanel');
    
    if (isComboCheckbox && comboPanel) {
        if (isComboCheckbox.checked) {
            comboPanel.style.display = 'block';
        } else {
            comboPanel.style.display = 'none';
        }
    }
}

/**
 * Validate Combo Component data before form submission
 */
function validateComboComponentData() {
    var isComboCheckbox = document.getElementById('is_combo_c');
    
    // Only validate if is_combo is checked
    if (!isComboCheckbox || !isComboCheckbox.checked) {
        return true;
    }
    
    var tbody = document.getElementById('comboComponentTableBody');
    if (!tbody) return true;
    
    var rows = tbody.getElementsByTagName('tr');
    
    if (rows.length === 0) {
        alert('Vui lòng thêm ít nhất một sản phẩm thành phần cho combo');
        return false;
    }
    
    for (var i = 0; i < rows.length; i++) {
        var productIdInput = rows[i].querySelector('input[name="cc_product_id[]"]');
        var qtyInput = rows[i].querySelector('input[name="cc_quantity[]"]');
        
        if (!productIdInput || !productIdInput.value) {
            alert('Vui lòng chọn sản phẩm thành phần cho dòng ' + (i + 1));
            return false;
        }
        
        if (!qtyInput || !qtyInput.value || parseFloat(qtyInput.value) <= 0) {
            alert('Vui lòng nhập số lượng hợp lệ cho dòng ' + (i + 1));
            return false;
        }
    }
    
    return true;
}

// Hook into form submission
document.addEventListener('DOMContentLoaded', function() {
    // Initialize combo component table
    var container = document.getElementById('comboComponentContainer');
    if (container && container.innerHTML.trim() === '') {
        container.innerHTML = initComboComponentTable();
    }
    
    // Setup discount field listeners
    var discountValue = document.getElementById('discount_value_c');
    if (discountValue) {
        discountValue.addEventListener('change', function() {
            calculateTotalPrice();
        });
        discountValue.addEventListener('keyup', function() {
            calculateTotalPrice();
        });
    }
    
    var discountType = document.getElementById('discount_type_c');
    if (discountType) {
        discountType.addEventListener('change', function() {
            calculateTotalPrice();
        });
    }
    
    // Auto-calculate on page load if combo components exist
    setTimeout(function() {
        var tbody = document.getElementById('comboComponentTableBody');
        if (tbody && tbody.getElementsByTagName('tr').length > 0) {
            calculateTotalPrice();
        }
    }, 500);
});

// Override form validation
if (typeof(check_form) !== 'undefined') {
    var original_check_form = check_form;
    check_form = function(formname) {
        if (!validateComboComponentData()) {
            return false;
        }
        return original_check_form(formname);
    };
}
