<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2026-02-26 03:10:52
$dictionary["sggt_price_policy"]["fields"]["sggt_price_policy_aos_products"] = array (
  'name' => 'sggt_price_policy_aos_products',
  'type' => 'link',
  'relationship' => 'sggt_price_policy_aos_products',
  'source' => 'non-db',
  'module' => 'AOS_Products',
  'bean_name' => 'AOS_Products',
  'vname' => 'LBL_SGGT_PRICE_POLICY_AOS_PRODUCTS_FROM_AOS_PRODUCTS_TITLE',
  'id_name' => 'sggt_price_policy_aos_productsaos_products_ida',
);
$dictionary["sggt_price_policy"]["fields"]["sggt_price_policy_aos_products_name"] = array (
  'name' => 'sggt_price_policy_aos_products_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_SGGT_PRICE_POLICY_AOS_PRODUCTS_FROM_AOS_PRODUCTS_TITLE',
  'save' => true,
  'id_name' => 'sggt_price_policy_aos_productsaos_products_ida',
  'link' => 'sggt_price_policy_aos_products',
  'table' => 'aos_products',
  'module' => 'AOS_Products',
  'rname' => 'name',
);
$dictionary["sggt_price_policy"]["fields"]["sggt_price_policy_aos_productsaos_products_ida"] = array (
  'name' => 'sggt_price_policy_aos_productsaos_products_ida',
  'type' => 'link',
  'relationship' => 'sggt_price_policy_aos_products',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_SGGT_PRICE_POLICY_AOS_PRODUCTS_FROM_SGGT_PRICE_POLICY_TITLE',
);

?>