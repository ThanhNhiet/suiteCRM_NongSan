<?php
/**
 * Custom field for displaying Price Policy in AOS_Products DetailView
 * Similar to line_items field in AOS_Invoices
 */
$dictionary['AOS_Products']['fields']['price_policy_display'] = array(
    'required' => false,
    'name' => 'price_policy_display',
    'vname' => 'LBL_PRICE_POLICY_DISPLAY',
    'type' => 'function',
    'source' => 'non-db',
    'massupdate' => 0,
    'importable' => 'false',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => false,
    'reportable' => false,
    'inline_edit' => false,
    'function' => array(
        'name' => 'display_price_policy',
        'returns' => 'html',
        'include' => 'custom/modules/AOS_Products/Price_Policy_Display.php'
    ),
);
