<?php

require "Model/home.php";


if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    if (isset($_GET['action']) && $_GET['action'] === 'createProduct') {
        createProduct($pdo, $_GET['productBarCode'], $_GET['name'], $_GET['quantity'], $_GET['stored_quantity']);
        echo json_encode(['success' => true]);
        exit();
    }
    if (isset($_GET['action']) && $_GET['action'] === 'getProduct') {
        $product = getProduct($pdo, $_GET['productBarCode']);
        echo json_encode(['success' => true, 'product' => $product]);
        exit();
    }
    if (isset($_GET['action']) && $_GET['action'] === 'updateProduct') {
        updateProduct($pdo, intval($_GET['productBarCode']), $_GET['stored_quantity']);
        echo json_encode(['success' => true]);
        exit();
    }
    if (isset($_GET['action']) && $_GET['action'] === 'deleteProduct') {
        deleteProduct($pdo, intval($_GET['productBarCode']));
        echo json_encode(['success' => true]);
        exit();
    }
    if (isset($_GET['action']) && $_GET['action'] === 'listProducts') {
        $search = isset($_GET['q']) ? $_GET['q'] : null;
        $items = listProducts($pdo, $search);
        echo json_encode(['success' => true, 'products' => $items]);
        exit();
    }
}
require "View/home.php";