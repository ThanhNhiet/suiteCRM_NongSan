<?php
/**
 * Custom DetailView for AOS_Products
 * Price Policy display is now handled by function field type in vardefs
 * (see custom/Extension/modules/AOS_Products/Ext/Vardefs/price_policy_display.php)
 */
require_once('modules/AOS_Products/views/view.detail.php');

class CustomAOS_ProductsViewDetail extends AOS_ProductsViewDetail {
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function display()
    {
        parent::display();
    }
}