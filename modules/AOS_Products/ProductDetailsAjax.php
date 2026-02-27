<?php
/**
 * AJAX endpoint to get product details (price, currency)
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : '';

if (empty($product_id)) {
    echo json_encode(array('error' => 'Product ID required'));
    exit;
}

$product = BeanFactory::getBean('AOS_Products', $product_id);

if (!$product || !$product->id) {
    echo json_encode(array('error' => 'Product not found'));
    exit;
}

$response = array(
    'id' => $product->id,
    'name' => $product->name,
    'price' => $product->price,
    'currency_id' => $product->currency_id
);

echo json_encode($response);
exit;
