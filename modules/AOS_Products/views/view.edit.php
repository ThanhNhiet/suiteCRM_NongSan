<?php
/**
 * Custom EditView for AOS_Products to handle Price Policy lines
 */
require_once('modules/AOS_Products/views/view.edit.php');

class CustomAOS_ProductsViewEdit extends AOS_ProductsViewEdit
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function display()
    {
        $this->loadPricePolicyLines();
        parent::display();
    }
    
    /**
     * Load existing Price Policy lines when editing
     */
    protected function loadPricePolicyLines()
    {
        global $mod_strings;
        
        $product_id = $this->bean->id;
        
        // Get available currencies
        $currencies = $this->getCurrencyList();
        $currenciesJson = json_encode($currencies);
        
        // Initialize the HTML with table structure
        $html = '<div style="margin-top: 15px; padding: 10px; border: 1px solid #ddd; background-color: #f9f9f9;">';
        $html .= '<h4 style="margin: 0 0 10px 0;">Chính sách giá</h4>';
        $html .= '<script>';
        $html .= 'var PRICE_POLICY_CURRENCIES = ' . $currenciesJson . ';';
        $html .= 'document.addEventListener("DOMContentLoaded", function() {';
        $html .= 'document.getElementById("pricePolicyContainer").innerHTML = initPricePolicyTable();';
        
        // If editing existing product, load price policies
        if (!empty($product_id)) {
            $db = DBManagerFactory::getInstance();
            
            $query = "SELECT p.id, p.name, a.id AS account_id, a.name AS account_name, p.price, p.currency_id
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
            
            while ($row = $db->fetchByAssoc($result)) {
                $policy_id = addslashes($row['id']);
                $account_id = addslashes($row['account_id']);
                $account_name = addslashes($row['account_name']);
                $price = $row['price'];
                $currency_id = !empty($row['currency_id']) ? addslashes($row['currency_id']) : '-99';
                
                $html .= "addPricePolicyLine('{$policy_id}', '{$account_id}', '{$account_name}', '{$price}', '{$currency_id}');";
            }
        }
        
        // If no existing lines (new product), add one empty line
        $html .= 'if (pricePolicyLineCount === 0) { addPricePolicyLine(); }';
        $html .= '});</script>';
        $html .= '<div id="pricePolicyContainer"></div>';
        $html .= '</div>';
        
        $this->ss->assign('PRICE_POLICY_LINES_HTML', $html);
    }
    
    /**
     * Get list of available currencies
     */
    protected function getCurrencyList()
    {
        $db = DBManagerFactory::getInstance();
        
        $currencies = array();
        
        // Always add system default US Dollar (id = -99, not stored in database)
        $currencies[] = array(
            'id' => '-99',
            'name' => 'US Dollars : $',
            'symbol' => '$',
            'iso' => 'USD'
        );
        
        // Query all custom currencies from database
        $query = "SELECT id, name, symbol, iso4217 FROM currencies WHERE deleted = 0 ORDER BY name ASC";
        $result = $db->query($query);
        
        while ($row = $db->fetchByAssoc($result)) {
            $currency_name = $row['name'];
            
            // Add symbol to name if available
            if (!empty($row['symbol'])) {
                $currency_name .= ' : ' . $row['symbol'];
            }
            
            $currencies[] = array(
                'id' => $row['id'],
                'name' => $currency_name,
                'symbol' => !empty($row['symbol']) ? $row['symbol'] : '',
                'iso' => !empty($row['iso4217']) ? $row['iso4217'] : ''
            );
        }
        
        return $currencies;
    }
}
