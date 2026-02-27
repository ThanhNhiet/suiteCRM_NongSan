<?php
 // created: 2026-02-27 03:44:12
$layout_defs["AOS_Products"]["subpanel_setup"]['sggt_combo_component_aos_products'] = array (
  'order' => 100,
  'module' => 'sggt_combo_component',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_SGGT_COMBO_COMPONENT_AOS_PRODUCTS_FROM_SGGT_COMBO_COMPONENT_TITLE',
  'get_subpanel_data' => 'sggt_combo_component_aos_products',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);
