<?php
// created: 2026-02-27 03:41:54
$dictionary["sggt_combo_compoent_aos_products"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'sggt_combo_compoent_aos_products' => 
    array (
      'lhs_module' => 'AOS_Products',
      'lhs_table' => 'aos_products',
      'lhs_key' => 'id',
      'rhs_module' => 'sggt_combo_compoent',
      'rhs_table' => 'sggt_combo_compoent',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'sggt_combo_compoent_aos_products_c',
      'join_key_lhs' => 'sggt_combo_compoent_aos_productsaos_products_ida',
      'join_key_rhs' => 'sggt_combo_compoent_aos_productssggt_combo_compoent_idb',
    ),
  ),
  'table' => 'sggt_combo_compoent_aos_products_c',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'sggt_combo_compoent_aos_productsaos_products_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'sggt_combo_compoent_aos_productssggt_combo_compoent_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'sggt_combo_compoent_aos_productsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'sggt_combo_compoent_aos_products_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'sggt_combo_compoent_aos_productsaos_products_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'sggt_combo_compoent_aos_products_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'sggt_combo_compoent_aos_productssggt_combo_compoent_idb',
      ),
    ),
  ),
);