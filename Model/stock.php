<?php

function getProducts($pdo) {
    $res = $pdo->prepare("SELECT * FROM `products`");
    $res->execute();
    return $res->fetchAll(PDO::FETCH_ASSOC);
}

function updateProduct($pdo, $productBarCode, $stored_quantity) {
    $res = $pdo->prepare("UPDATE `products` SET stored_quantity = $stored_quantity WHERE barcode = $productBarCode");
    $res->execute();
}