<?php
$module_name = 'sggt_combo_compoent';
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 'name',
          1 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'sggt_combo_compoent_aos_products_name',
            'label' => 'LBL_SGGT_COMBO_COMPOENT_AOS_PRODUCTS_FROM_AOS_PRODUCTS_TITLE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'product_component',
            'studio' => 'visible',
            'label' => 'LBL_PRODUCT_COMPONENT',
          ),
          1 => 
          array (
            'name' => 'currency_id',
            'studio' => 'visible',
            'label' => 'LBL_CURRENCY',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'standard_price',
            'label' => 'LBL_STANDARD_PRICE',
          ),
          1 => 
          array (
            'name' => 'quantity',
            'label' => 'LBL_QUANTITY',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'subtotal',
            'label' => 'LBL_SUBTOTAL',
          ),
          1 => 'description',
        ),
      ),
    ),
  ),
);
;
?>
