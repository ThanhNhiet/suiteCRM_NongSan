<?php
$module_name = 'sggt_price_policy';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'SGGT_PRICE_POLICY_AOS_PRODUCTS_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_SGGT_PRICE_POLICY_AOS_PRODUCTS_FROM_AOS_PRODUCTS_TITLE',
    'id' => 'SGGT_PRICE_POLICY_AOS_PRODUCTSAOS_PRODUCTS_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'PRICE' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_PRICE',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
  ),
  'DATE_MODIFIED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => true,
  ),
);
;
?>
