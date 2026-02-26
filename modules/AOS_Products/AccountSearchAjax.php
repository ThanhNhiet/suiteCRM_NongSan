<?php
/**
 * AJAX handler for searching Accounts in Price Policy edit
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class AOS_ProductsAccountSearch
{
    public function getAccountList()
    {
        global $db;
        
        $search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
        
        if (strlen($search) < 2) {
            echo json_encode([]);
            return;
        }
        
        $search = $db->quote($search);
        $search = str_replace("'", "", $search); // Remove quotes for LIKE
        
        $query = "SELECT id, name 
                  FROM accounts 
                  WHERE deleted = 0 
                    AND name LIKE '%{$search}%' 
                  ORDER BY name 
                  LIMIT 10";
        
        $result = $db->query($query);
        $accounts = [];
        
        while ($row = $db->fetchByAssoc($result)) {
            $accounts[] = [
                'id' => $row['id'],
                'name' => $row['name']
            ];
        }
        
        echo json_encode($accounts);
    }
}

// Handle AJAX request
if (isset($_REQUEST['method']) && $_REQUEST['method'] === 'get_account_list') {
    $handler = new AOS_ProductsAccountSearch();
    $handler->getAccountList();
    die();
}
