<?php
/**
 * Display Price Policy for Product Detail View
 * Similar to Line_Items.php in AOS_Products_Quotes
 */

/**
 * Function to display price policies in AOS_Products DetailView
 * 
 * @param object $focus - The current bean
 * @param string $field - The field name
 * @param mixed $value - The field value
 * @param string $view - The view type (EditView/DetailView)
 * @return string HTML output
 */
function display_price_policy($focus, $field, $value, $view)
{
    global $sugar_config, $locale, $app_list_strings, $mod_strings, $current_language;
    
    $html = '';
    
    if ($view == 'DetailView') {
        $product_id = $focus->id;
        
        if (empty($product_id)) {
            return '<div style="padding: 10px; color: #888;">Không có dữ liệu</div>';
        }
        
        $db = DBManagerFactory::getInstance();
        
        // Query to get price policies linked to this product
        $query = "SELECT p.id, p.name AS policy_name, a.id AS account_id, a.name AS account_name, 
                         p.price, p.currency_id, p.date_modified 
                  FROM sggt_price_policy p
                  INNER JOIN sggt_price_policy_aos_products_c rel 
                    ON p.id = rel.sggt_price_policy_aos_productssggt_price_policy_idb
                  LEFT JOIN accounts a 
                    ON p.account_id_c = a.id AND a.deleted = 0
                  WHERE rel.sggt_price_policy_aos_productsaos_products_ida = '{$product_id}' 
                    AND p.deleted = 0 
                    AND rel.deleted = 0
                  ORDER BY p.date_modified DESC";
        
        $result = $db->query($query);
        
        // Build HTML table similar to Line Items style
        $html .= "<table border='0' width='100%' cellpadding='0' cellspacing='0'>";
        
        // Header row
        $html .= "<tr>";
        $html .= "<td width='5%' class='tabDetailViewDL' style='text-align: center; padding:5px;' scope='row'>#</td>";
        $html .= "<td width='25%' class='tabDetailViewDL' style='text-align: left; padding:5px;' scope='row'>Tên chính sách</td>";
        $html .= "<td width='25%' class='tabDetailViewDL' style='text-align: left; padding:5px;' scope='row'>Khách hàng / Đối tác</td>";
        $html .= "<td width='20%' class='tabDetailViewDL' style='text-align: right; padding:5px;' scope='row'>Giá</td>";
        $html .= "<td width='25%' class='tabDetailViewDL' style='text-align: left; padding:5px;' scope='row'>Ngày cập nhật</td>";
        $html .= "</tr>";
        
        $row_count = 0;
        while ($row = $db->fetchByAssoc($result)) {
            $row_count++;
            
            // Format price with currency
            $price_formatted = format_number($row['price'], 0, 0);
            
            // Create link for policy name
            $policy_link = "<a href='index.php?module=sggt_price_policy&action=DetailView&record={$row['id']}' class='tabDetailViewDFLink'>{$row['policy_name']}</a>";
            
            // Create link for account name if exists
            $account_display = '';
            if (!empty($row['account_name'])) {
                $account_display = "<a href='index.php?module=Accounts&action=DetailView&record={$row['account_id']}' class='tabDetailViewDFLink'>{$row['account_name']}</a>";
            }
            
            // Format date
            $date_formatted = '';
            if (!empty($row['date_modified'])) {
                $timedate = TimeDate::getInstance();
                $date_formatted = $timedate->to_display_date_time($row['date_modified']);
            }
            
            $html .= "<tr>";
            $html .= "<td class='tabDetailViewDF' style='text-align: center; padding:5px;'>{$row_count}</td>";
            $html .= "<td class='tabDetailViewDF' style='padding:5px;'>{$policy_link}</td>";
            $html .= "<td class='tabDetailViewDF' style='padding:5px;'>{$account_display}</td>";
            $html .= "<td class='tabDetailViewDF' style='text-align: right; padding:5px;'>{$price_formatted}</td>";
            $html .= "<td class='tabDetailViewDF' style='padding:5px;'>{$date_formatted}</td>";
            $html .= "</tr>";
        }
        
        if ($row_count == 0) {
            $html .= "<tr><td colspan='5' class='tabDetailViewDF' style='padding: 15px; text-align: center; color: #888;'>Không có chính sách giá nào</td></tr>";
        }
        
        $html .= "</table>";
        
    } elseif ($view == 'EditView') {
        // In EditView, just show a message or nothing
        $html .= '<div style="padding: 10px; color: #666; font-style: italic;">Chính sách giá được quản lý ở phần subpanel bên dưới</div>';
    }
    
    return $html;
}
