<?php

global $pdo;

function createProduct($pdo, $productBarCode, $name, $quantity, $stored_quantity) {
    try {
        $res = $pdo->prepare("INSERT INTO products (barcode, name, quantity,stored_quantity) VALUES (:productBarCode, :name, :quantity, :stored_quantity)");
        $res->bindParam(':productBarCode', $productBarCode);
        $res->bindParam(':name', $name);
        $res->bindParam(':quantity', $quantity);
        $res->bindParam(':stored_quantity', $stored_quantity);
        $res->execute();
    } catch (Exception $e) {
        var_dump($e->getMessage());
    }
}

function updateProduct($pdo, $productBarCode, $stored_quantity) {
    try {
        $res = $pdo->prepare("UPDATE `products` SET stored_quantity = $stored_quantity WHERE barcode = $productBarCode");
        $res->execute();
    } catch (Exception $e) {
        var_dump($e->getMessage());
    }
}

function getProduct($pdo, $productBarCode) {
    try {
        $res = $pdo->prepare("SELECT * FROM `products` WHERE barcode = :productBarCode");
        $res->bindParam(':productBarCode', $productBarCode);
        $res->execute();
        return $res->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        var_dump($e->getMessage());
    }
}

function deleteProduct($pdo, $productBarCode) {
    try {
        $res = $pdo->prepare("DELETE FROM `products` WHERE barcode = $productBarCode");
        $res->execute();
    } catch (Exception $e) {
        var_dump($e->getMessage());
    }
}