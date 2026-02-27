<?php
$module_name = 'sggt_combo_component';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'SGGT_COMBO_COMPOENT_AOS_PRODUCTS_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_SGGT_COMBO_COMPOENT_AOS_PRODUCTS_FROM_AOS_PRODUCTS_TITLE',
    'id' => 'SGGT_COMBO_COMPOENT_AOS_PRODUCTSAOS_PRODUCTS_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'PRODUCT_COMPONENT' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_PRODUCT_COMPONENT',
    'id' => 'AOS_PRODUCTS_ID1_C',
    'link' => true,
    'width' => '10%',
    'default' => true,
  ),
  'SUBTOTAL' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_SUBTOTAL',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
  ),
);
;
?>
