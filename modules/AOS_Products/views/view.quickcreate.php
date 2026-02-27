<?php
/**
 * Custom QuickCreate View for AOS_Products to handle Combo Components
 */
require_once('modules/AOS_Products/views/view.edit.php');

class AOS_ProductsViewQuickCreate extends AOS_ProductsViewEdit
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function display()
    {
        $this->loadComboComponents();
        parent::display();
    }
    
    /**
     * Load existing Combo Components when editing
     */
    protected function loadComboComponents()
    {
        global $mod_strings;
        
        $product_id = $this->bean->id;
        
        // Initialize the HTML with table structure
        $html = '<div id="comboComponentPanel" style="margin-top: 15px; padding: 10px; border: 1px solid #ddd; background-color: #f9f9f9; display: none;">';
        $html .= '<h4 style="margin: 0 0 10px 0;">Thành phần Combo</h4>';
        $html .= '<script>';
        $html .= 'document.addEventListener("DOMContentLoaded", function() {';
        $html .= 'var container = document.getElementById("comboComponentContainer");';
        $html .= 'if (container) {';
        $html .= 'container.innerHTML = initComboComponentTable();';
        
        // If editing existing product with combo components, load them
        if (!empty($product_id)) {
            $db = DBManagerFactory::getInstance();
            
            $query = "SELECT cc.id, cc.name, p.id AS product_id, p.name AS product_name, 
                             cc.standard_price, cc.quantity, cc.currency_id, cc.subtotal
                      FROM sggt_combo_component cc
                      INNER JOIN sggt_combo_component_aos_products_c rel 
                        ON cc.id = rel.sggt_combo_component_aos_productssggt_combo_component_idb
                      LEFT JOIN aos_products p 
                        ON cc.aos_products_id1_c = p.id AND p.deleted = 0
                      WHERE rel.sggt_combo_component_aos_productsaos_products_ida = '{$product_id}' 
                        AND cc.deleted = 0 
                        AND rel.deleted = 0
                      ORDER BY cc.date_modified ASC";
            
            $result = $db->query($query);
            
            while ($row = $db->fetchByAssoc($result)) {
                $component_id = addslashes($row['id']);
                $product_id_val = addslashes($row['product_id']);
                $product_name = addslashes($row['product_name']);
                $standard_price = $row['standard_price'];
                $quantity = $row['quantity'];
                $currency_id = !empty($row['currency_id']) ? addslashes($row['currency_id']) : '-99';
                
                $html .= "addComboComponentLine('{$component_id}', '{$product_id_val}', '{$product_name}', '{$standard_price}', '{$quantity}', '{$currency_id}');";
            }
        }
        
        // If no existing lines (new product), add one empty line if is_combo is checked
        $html .= 'var isComboCheckbox = document.getElementById("is_combo_c");';
        $html .= 'if (isComboCheckbox && isComboCheckbox.checked && comboComponentLineCount === 0) {';
        $html .= 'addComboComponentLine();';
        $html .= '}';
        
        $html .= '}';
        $html .= '});</script>';
        $html .= '<div id="comboComponentContainer"></div>';
        $html .= '</div>';
        
        $this->ss->assign('COMBO_COMPONENT_HTML', $html);
    }
}
