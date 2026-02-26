<?php 
 //WARNING: The contents of this file are auto-generated


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


// created: 2026-02-26 03:10:52
$dictionary["AOS_Products"]["fields"]["sggt_price_policy_aos_products"] = array (
  'name' => 'sggt_price_policy_aos_products',
  'type' => 'link',
  'relationship' => 'sggt_price_policy_aos_products',
  'source' => 'non-db',
  'module' => 'sggt_price_policy',
  'bean_name' => false,
  'side' => 'right',
  'vname' => 'LBL_SGGT_PRICE_POLICY_AOS_PRODUCTS_FROM_SGGT_PRICE_POLICY_TITLE',
);


 // created: 2026-02-26 03:30:26
$dictionary['AOS_Products']['fields']['weight_c']['inline_edit']='1';
$dictionary['AOS_Products']['fields']['weight_c']['labelValue']='weight';

 
?>