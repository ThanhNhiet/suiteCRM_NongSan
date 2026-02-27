<?php
/**
 * Custom field for displaying Combo Components in AOS_Products DetailView
 * Similar to line_items field in AOS_Invoices
 */
$dictionary['AOS_Products']['fields']['combo_component_display'] = array(
    'required' => false,
    'name' => 'combo_component_display',
    'vname' => 'LBL_COMBO_COMPONENT_DISPLAY',
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
        'name' => 'display_combo_component',
        'returns' => 'html',
        'include' => 'custom/modules/AOS_Products/Combo_Component_Display.php'
    ),
);
