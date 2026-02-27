<?php
/**
 * Logic Hooks for AOS_Products module
 */

$hook_array['after_save'][] = array(
    1,
    'Save Price Policy Lines',
    'custom/modules/AOS_Products/PricePolicyHook.php',
    'AOS_Products_PricePolicyHook',
    'savePricePolicyLines'
);

$hook_array['after_save'][] = array(
    2,
    'Save Combo Component Lines',
    'custom/modules/AOS_Products/ComboComponentHook.php',
    'AOS_Products_ComboComponentHook',
    'saveComboComponents'
);
