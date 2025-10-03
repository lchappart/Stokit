<?php 
require "Model/stock.php";

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    if (isset($_GET['action']) && $_GET['action'] === 'getProducts') {
        $products = getProducts($pdo);
        echo json_encode(['success' => true, 'products' => $products]);
        exit();
    }
    if (isset($_GET['action']) && $_GET['action'] === 'updateProduct') {
        updateProduct($pdo, $_GET['productBarCode'], $_GET['stored_quantity']);
        echo json_encode(['success' => true]);
        exit();
    }
}
require "View/stock.php";