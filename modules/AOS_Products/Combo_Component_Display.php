<?php
/**
 * Display Combo Components for Product Detail View
 * Similar to Line_Items.php in AOS_Products_Quotes
 */

/**
 * Function to display combo components in AOS_Products DetailView
 * 
 * @param object $focus - The current bean
 * @param string $field - The field name
 * @param mixed $value - The field value
 * @param string $view - The view type (EditView/DetailView)
 * @return string HTML output
 */
function display_combo_component($focus, $field, $value, $view)
{
    global $sugar_config, $locale, $app_list_strings, $mod_strings, $current_language;
    
    $html = '';
    
    if ($view == 'DetailView') {
        $product_id = $focus->id;
        
        if (empty($product_id)) {
            return '<div style="padding: 10px; color: #888;">Không có dữ liệu</div>';
        }
        
        $db = DBManagerFactory::getInstance();
        
        // Query to get combo components linked to this product
        $query = "SELECT cc.id, cc.aos_products_id1_c as product_id, p.name as product_name, 
                         cc.quantity, cc.standard_price, cc.currency_id, cc.subtotal,
                         c.name AS currency_name, c.symbol AS currency_symbol
                  FROM sggt_combo_component cc
                  INNER JOIN sggt_combo_component_aos_products_c rel 
                    ON cc.id = rel.sggt_combo_component_aos_productssggt_combo_component_idb
                  LEFT JOIN aos_products p 
                    ON cc.aos_products_id1_c = p.id AND p.deleted = 0
                  LEFT JOIN currencies c
                    ON cc.currency_id = c.id AND c.deleted = 0
                  WHERE rel.sggt_combo_component_aos_productsaos_products_ida = '{$product_id}' 
                    AND cc.deleted = 0 
                    AND rel.deleted = 0
                  ORDER BY cc.date_entered ASC";
        
        $result = $db->query($query);
        
        // Build HTML table similar to Line Items style
        $html .= "<table border='0' width='100%' cellpadding='0' cellspacing='0'>";
        
        // Header row
        $html .= "<tr>";
        $html .= "<td width='5%' class='tabDetailViewDL' style='text-align: center; padding:5px;' scope='row'>#</td>";
        $html .= "<td width='40%' class='tabDetailViewDL' style='text-align: left; padding:5px;' scope='row'>Sản phẩm thành phần</td>";
        $html .= "<td width='15%' class='tabDetailViewDL' style='text-align: right; padding:5px;' scope='row'>Giá niêm yết</td>";
        $html .= "<td width='10%' class='tabDetailViewDL' style='text-align: center; padding:5px;' scope='row'>Số lượng</td>";
        $html .= "<td width='15%' class='tabDetailViewDL' style='text-align: right; padding:5px;' scope='row'>Thành tiền</td>";
        $html .= "<td width='15%' class='tabDetailViewDL' style='text-align: left; padding:5px;' scope='row'>Mệnh giá</td>";
        $html .= "</tr>";
        
        $row_count = 0;
        $total_price = 0;
        
        while ($row = $db->fetchByAssoc($result)) {
            $row_count++;
            
            // Format numbers
            $price_formatted = format_number($row['standard_price'], 0, 0);
            $subtotal_formatted = format_number($row['subtotal'], 0, 0);
            $quantity_formatted = format_number($row['quantity'], 0, 0);
            
            // Create link for product name
            $product_link = '';
            if (!empty($row['product_name']) && !empty($row['product_id'])) {
                $product_link = "<a href='index.php?module=AOS_Products&action=DetailView&record={$row['product_id']}' class='tabDetailViewDFLink'>{$row['product_name']}</a>";
            } else {
                $product_link = '<span style="color: #888;">N/A</span>';
            }
            
            // Get currency display
            $currency_display = 'US Dollars'; // Default
            if (!empty($row['currency_name'])) {
                $currency_display = $row['currency_name'];
            } else if (empty($row['currency_id']) || $row['currency_id'] == '-99') {
                $currency_display = 'US Dollars';
            }
            
            $html .= "<tr>";
            $html .= "<td class='tabDetailViewDF' style='text-align: center; padding:5px;'>{$row_count}</td>";
            $html .= "<td class='tabDetailViewDF' style='padding:5px;'>{$product_link}</td>";
            $html .= "<td class='tabDetailViewDF' style='text-align: right; padding:5px;'>{$price_formatted}</td>";
            $html .= "<td class='tabDetailViewDF' style='text-align: center; padding:5px;'>{$quantity_formatted}</td>";
            $html .= "<td class='tabDetailViewDF' style='text-align: right; padding:5px; font-weight: bold;'>{$subtotal_formatted}</td>";
            $html .= "<td class='tabDetailViewDF' style='padding:5px;'>{$currency_display}</td>";
            $html .= "</tr>";
            
            $total_price += $row['subtotal'];
        }
        
        if ($row_count == 0) {
            $html .= "<tr>";
            $html .= "<td colspan='6' class='tabDetailViewDF' style='text-align: center; padding: 15px; color: #888;'>Không có sản phẩm thành phần</td>";
            $html .= "</tr>";
        } else {
            // Total row
            $total_formatted = format_number($total_price, 0, 0);
            $html .= "<tr>";
            $html .= "<td colspan='4' class='tabDetailViewDF' style='text-align: right; padding:10px; font-weight: bold; background-color: #f5f5f5;'>Tổng cộng:</td>";
            $html .= "<td class='tabDetailViewDF' style='text-align: right; padding:10px; font-weight: bold; font-size: 14px; color: #c00; background-color: #f5f5f5;'>{$total_formatted}</td>";
            $html .= "<td class='tabDetailViewDF' style='padding:10px; background-color: #f5f5f5;'></td>";
            $html .= "</tr>";
        }
        
        $html .= "</table>";
    }
    
    return $html;
}
