<?php
/**
 * Logic Hook for saving Price Policy lines when Product is saved
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class AOS_Products_PricePolicyHook
{
    /**
     * After save hook to process Price Policy lines
     */
    public function savePricePolicyLines($bean, $event, $arguments)
    {
        global $db;
        
        // Check if we have price policy data from the form
        if (!isset($_POST['pp_account_id']) || !is_array($_POST['pp_account_id'])) {
            return;
        }
        
        $product_id = $bean->id;
        $pp_ids = isset($_POST['pp_id']) ? $_POST['pp_id'] : [];
        $pp_account_ids = $_POST['pp_account_id'];
        $pp_account_names = isset($_POST['pp_account_name']) ? $_POST['pp_account_name'] : [];
        $pp_prices = isset($_POST['pp_price']) ? $_POST['pp_price'] : [];
        $pp_currency_ids = isset($_POST['pp_currency_id']) ? $_POST['pp_currency_id'] : [];
        
        // Get existing price policy IDs for this product
        $existing_ids = $this->getExistingPricePolicyIds($product_id);
        $updated_ids = [];
        
        // Process each line
        for ($i = 0; $i < count($pp_account_ids); $i++) {
            $account_id = $pp_account_ids[$i];
            $price = isset($pp_prices[$i]) ? $pp_prices[$i] : 0;
            $policy_id = isset($pp_ids[$i]) && !empty($pp_ids[$i]) ? $pp_ids[$i] : null;
            $currency_id = isset($pp_currency_ids[$i]) && !empty($pp_currency_ids[$i]) ? $pp_currency_ids[$i] : '-99';
            
            // Skip if account is not selected
            if (empty($account_id)) {
                continue;
            }
            
            // Clean price value (remove commas, spaces)
            $price = str_replace([',', ' '], '', $price);
            $price = floatval($price);
            
            if ($policy_id && !empty($policy_id)) {
                // Update existing price policy
                $this->updatePricePolicy($policy_id, $account_id, $price, $currency_id);
                $updated_ids[] = $policy_id;
            } else {
                // Create new price policy
                $new_policy_id = $this->createPricePolicy($product_id, $account_id, $price, $currency_id);
                if ($new_policy_id) {
                    $updated_ids[] = $new_policy_id;
                }
            }
        }
        
        // Delete price policies that were removed
        $deleted_ids = array_diff($existing_ids, $updated_ids);
        foreach ($deleted_ids as $delete_id) {
            $this->deletePricePolicy($delete_id);
        }
    }
    
    /**
     * Get existing Price Policy IDs for a product
     */
    protected function getExistingPricePolicyIds($product_id)
    {
        global $db;
        
        $query = "SELECT p.id
                  FROM sggt_price_policy p
                  INNER JOIN sggt_price_policy_aos_products_c rel 
                    ON p.id = rel.sggt_price_policy_aos_productssggt_price_policy_idb
                  WHERE rel.sggt_price_policy_aos_productsaos_products_ida = '{$product_id}' 
                    AND p.deleted = 0 
                    AND rel.deleted = 0";
        
        $result = $db->query($query);
        $ids = [];
        
        while ($row = $db->fetchByAssoc($result)) {
            $ids[] = $row['id'];
        }
        
        return $ids;
    }
    
    /**
     * Create new Price Policy
     */
    protected function createPricePolicy($product_id, $account_id, $price, $currency_id = '-99')
    {
        require_once('modules/sggt_price_policy/sggt_price_policy.php');
        
        $policy = BeanFactory::newBean('sggt_price_policy');
        $policy->name = "CSG " . $this->getAccountName($account_id);
        $policy->account_id_c = $account_id;
        $policy->price = $price;
        $policy->currency_id = $currency_id;
        $policy->save();
        
        // Create relationship
        $this->createRelationship($product_id, $policy->id);
        
        return $policy->id;
    }
    
    /**
     * Update existing Price Policy
     */
    protected function updatePricePolicy($policy_id, $account_id, $price, $currency_id = '-99')
    {
        require_once('modules/sggt_price_policy/sggt_price_policy.php');
        
        $policy = BeanFactory::getBean('sggt_price_policy', $policy_id);
        if ($policy && $policy->id) {
            $policy->account_id_c = $account_id;
            $policy->price = $price;
            $policy->currency_id = $currency_id;
            $policy->name = "CSG " . $this->getAccountName($account_id);
            $policy->save();
        }
    }
    
    /**
     * Delete Price Policy (soft delete)
     */
    protected function deletePricePolicy($policy_id)
    {
        require_once('modules/sggt_price_policy/sggt_price_policy.php');
        
        $policy = BeanFactory::getBean('sggt_price_policy', $policy_id);
        if ($policy && $policy->id) {
            $policy->mark_deleted($policy_id);
        }
    }
    
    /**
     * Create relationship between Product and Price Policy
     */
    protected function createRelationship($product_id, $policy_id)
    {
        global $db;
        
        $rel_id = create_guid();
        $now = date('Y-m-d H:i:s');
        
        $query = "INSERT INTO sggt_price_policy_aos_products_c 
                  (id, date_modified, deleted, sggt_price_policy_aos_productsaos_products_ida, sggt_price_policy_aos_productssggt_price_policy_idb)
                  VALUES ('{$rel_id}', '{$now}', 0, '{$product_id}', '{$policy_id}')";
        
        $db->query($query);
    }
    
    /**
     * Get Account name by ID
     */
    protected function getAccountName($account_id)
    {
        global $db;
        
        $query = "SELECT name FROM accounts WHERE id = '{$account_id}' AND deleted = 0";
        $result = $db->query($query);
        $row = $db->fetchByAssoc($result);
        
        return $row ? $row['name'] : 'Unknown';
    }
}
