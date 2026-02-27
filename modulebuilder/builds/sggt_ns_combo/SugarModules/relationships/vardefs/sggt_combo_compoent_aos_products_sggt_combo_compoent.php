<?php
// created: 2026-02-27 03:41:54
$dictionary["sggt_combo_compoent"]["fields"]["sggt_combo_compoent_aos_products"] = array (
  'name' => 'sggt_combo_compoent_aos_products',
  'type' => 'link',
  'relationship' => 'sggt_combo_compoent_aos_products',
  'source' => 'non-db',
  'module' => 'AOS_Products',
  'bean_name' => 'AOS_Products',
  'vname' => 'LBL_SGGT_COMBO_COMPOENT_AOS_PRODUCTS_FROM_AOS_PRODUCTS_TITLE',
  'id_name' => 'sggt_combo_compoent_aos_productsaos_products_ida',
);
$dictionary["sggt_combo_compoent"]["fields"]["sggt_combo_compoent_aos_products_name"] = array (
  'name' => 'sggt_combo_compoent_aos_products_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_SGGT_COMBO_COMPOENT_AOS_PRODUCTS_FROM_AOS_PRODUCTS_TITLE',
  'save' => true,
  'id_name' => 'sggt_combo_compoent_aos_productsaos_products_ida',
  'link' => 'sggt_combo_compoent_aos_products',
  'table' => 'aos_products',
  'module' => 'AOS_Products',
  'rname' => 'name',
);
$dictionary["sggt_combo_compoent"]["fields"]["sggt_combo_compoent_aos_productsaos_products_ida"] = array (
  'name' => 'sggt_combo_compoent_aos_productsaos_products_ida',
  'type' => 'link',
  'relationship' => 'sggt_combo_compoent_aos_products',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_SGGT_COMBO_COMPOENT_AOS_PRODUCTS_FROM_SGGT_COMBO_COMPOENT_TITLE',
);
