<?php
/**
 * Logic Hook for saving Combo Components and calculating Product price
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class AOS_Products_ComboComponentHook
{
    /**
     * After save hook to process Combo Component lines
     */
    public function saveComboComponents($bean, $event, $arguments)
    {
        global $db;
        
        // If is_combo is NOT checked, delete all existing combo components (if any)
        if (empty($bean->is_combo_c)) {
            $existing_ids = $this->getExistingComboComponentIds($bean->id);
            foreach ($existing_ids as $delete_id) {
                $this->deleteComboComponent($delete_id);
            }
            return;
        }
        
        // Check if we have combo component data from the form
        if (!isset($_POST['cc_product_id']) || !is_array($_POST['cc_product_id'])) {
            return;
        }
        
        $product_id = $bean->id;
        $cc_ids = isset($_POST['cc_id']) ? $_POST['cc_id'] : [];
        $cc_product_ids = $_POST['cc_product_id'];
        $cc_standard_prices = isset($_POST['cc_standard_price']) ? $_POST['cc_standard_price'] : [];
        $cc_quantities = isset($_POST['cc_quantity']) ? $_POST['cc_quantity'] : [];
        $cc_currency_ids = isset($_POST['cc_currency_id']) ? $_POST['cc_currency_id'] : [];
        $cc_subtotals = isset($_POST['cc_subtotal']) ? $_POST['cc_subtotal'] : [];
        
        // Get existing combo component IDs for this product
        $existing_ids = $this->getExistingComboComponentIds($product_id);
        $updated_ids = [];
        
        // Process each line
        for ($i = 0; $i < count($cc_product_ids); $i++) {
            $component_product_id = $cc_product_ids[$i];
            $standard_price = isset($cc_standard_prices[$i]) ? $this->cleanNumber($cc_standard_prices[$i]) : 0;
            $quantity = isset($cc_quantities[$i]) ? $this->cleanNumber($cc_quantities[$i]) : 1;
            $currency_id = isset($cc_currency_ids[$i]) && !empty($cc_currency_ids[$i]) ? $cc_currency_ids[$i] : '-99';
            $subtotal = isset($cc_subtotals[$i]) ? $this->cleanNumber($cc_subtotals[$i]) : 0;
            $component_id = isset($cc_ids[$i]) && !empty($cc_ids[$i]) ? $cc_ids[$i] : null;
            
            // Skip if product is not selected
            if (empty($component_product_id)) {
                continue;
            }
            
            if ($component_id && !empty($component_id)) {
                // Update existing combo component
                $this->updateComboComponent($component_id, $component_product_id, $standard_price, $quantity, $currency_id, $subtotal);
                $updated_ids[] = $component_id;
            } else {
                // Create new combo component
                $new_component_id = $this->createComboComponent($product_id, $component_product_id, $standard_price, $quantity, $currency_id, $subtotal);
                if ($new_component_id) {
                    $updated_ids[] = $new_component_id;
                }
            }
        }
        
        // Delete combo components that were removed
        $deleted_ids = array_diff($existing_ids, $updated_ids);
        foreach ($deleted_ids as $delete_id) {
            $this->deleteComboComponent($delete_id);
        }
    }
    
    /**
     * Get existing Combo Component IDs for a product
     */
    protected function getExistingComboComponentIds($product_id)
    {
        global $db;
        
        $query = "SELECT cc.id
                  FROM sggt_combo_component cc
                  INNER JOIN sggt_combo_component_aos_products_c rel 
                    ON cc.id = rel.sggt_combo_component_aos_productssggt_combo_component_idb
                  WHERE rel.sggt_combo_component_aos_productsaos_products_ida = '{$product_id}' 
                    AND cc.deleted = 0 
                    AND rel.deleted = 0";
        
        $result = $db->query($query);
        $ids = [];
        
        while ($row = $db->fetchByAssoc($result)) {
            $ids[] = $row['id'];
        }
        
        return $ids;
    }
    
    /**
     * Create new Combo Component
     */
    protected function createComboComponent($product_id, $component_product_id, $standard_price, $quantity, $currency_id, $subtotal)
    {
        require_once('modules/sggt_combo_component/sggt_combo_component.php');
        
        $component = BeanFactory::newBean('sggt_combo_component');
        $component->name = "Component " . $this->getProductName($component_product_id);
        $component->aos_products_id1_c = $component_product_id;
        $component->standard_price = $standard_price;
        $component->quantity = $quantity;
        $component->currency_id = $currency_id;
        $component->subtotal = $subtotal;
        $component->save();
        
        // Create relationship
        $this->createRelationship($product_id, $component->id);
        
        return $component->id;
    }
    
    /**
     * Update existing Combo Component
     */
    protected function updateComboComponent($component_id, $component_product_id, $standard_price, $quantity, $currency_id, $subtotal)
    {
        require_once('modules/sggt_combo_component/sggt_combo_component.php');
        
        $component = BeanFactory::getBean('sggt_combo_component', $component_id);
        if ($component && $component->id) {
            $component->aos_products_id1_c = $component_product_id;
            $component->standard_price = $standard_price;
            $component->quantity = $quantity;
            $component->currency_id = $currency_id;
            $component->subtotal = $subtotal;
            $component->name = "Component " . $this->getProductName($component_product_id);
            $component->save();
        }
    }
    
    /**
     * Delete Combo Component (soft delete)
     */
    protected function deleteComboComponent($component_id)
    {
        require_once('modules/sggt_combo_component/sggt_combo_component.php');
        
        $component = BeanFactory::getBean('sggt_combo_component', $component_id);
        if ($component && $component->id) {
            $component->mark_deleted($component_id);
        }
    }
    
    /**
     * Create relationship between Product and Combo Component
     */
    protected function createRelationship($product_id, $component_id)
    {
        global $db;
        
        $rel_id = create_guid();
        $now = date('Y-m-d H:i:s');
        
        $query = "INSERT INTO sggt_combo_component_aos_products_c 
                  (id, date_modified, deleted, sggt_combo_component_aos_productsaos_products_ida, sggt_combo_component_aos_productssggt_combo_component_idb)
                  VALUES ('{$rel_id}', '{$now}', 0, '{$product_id}', '{$component_id}')";
        
        $db->query($query);
    }
    
    /**
     * Get Product name by ID
     */
    protected function getProductName($product_id)
    {
        global $db;
        
        $query = "SELECT name FROM aos_products WHERE id = '{$product_id}' AND deleted = 0";
        $result = $db->query($query);
        $row = $db->fetchByAssoc($result);
        
        return $row ? $row['name'] : 'Unknown';
    }
    
    /**
     * Clean number string (remove commas, spaces)
     */
    protected function cleanNumber($value)
    {
        $cleaned = str_replace([',', ' '], '', $value);
        return floatval($cleaned);
    }
}
