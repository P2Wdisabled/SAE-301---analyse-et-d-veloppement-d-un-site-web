<?php
// requester.php

require_once 'modele.php';

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'getAllProducts':
            $categoryId = isset($_GET['categoryId']) ? intval($_GET['categoryId']) : null;
            getAllProducts($categoryId);
            break;
        case 'getAllCategories':
            getAllCategories();
            break;
        case 'getProductDetails':
            $productId = isset($_GET['productId']) ? intval($_GET['productId']) : null;
            if ($productId) {
                getProductDetails($productId);
            } else {
                echo json_encode(['error' => 'ID du produit manquant']);
            }
            break;
        case 'addToCart':
            $productId = isset($_POST['productId']) ? intval($_POST['productId']) : null;
            $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
            $options = isset($_POST['options']) ? json_decode($_POST['options'], true) : [];

            if ($productId && $quantity) {
                addToCart($productId, $quantity, $options);
            } else {
                echo json_encode(['error' => 'Données manquantes pour ajouter au panier']);
            }
            break;
        case 'getCart':
            getCart();
            break;
        case 'updateCartItem':
            $index = isset($_POST['index']) ? intval($_POST['index']) : null;
            $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : null;

            if ($index !== null && $quantity !== null) {
                updateCartItem($index, $quantity);
            } else {
                echo json_encode(['error' => 'Données manquantes pour mettre à jour le panier']);
            }
            break;
        case 'removeCartItem':
            $index = isset($_POST['index']) ? intval($_POST['index']) : null;

            if ($index !== null) {
                removeCartItem($index);
            } else {
                echo json_encode(['error' => 'Données manquantes pour supprimer du panier']);
            }
            break;
        case 'register':
            $username = isset($_POST['username']) ? $_POST['username'] : null;
            $password = isset($_POST['password']) ? $_POST['password'] : null;

            if ($username && $password) {
                registerUser($username, $password);
            } else {
                echo json_encode(['error' => 'Données manquantes pour l\'inscription']);
            }
            break;
        case 'login':
            $username = isset($_POST['username']) ? $_POST['username'] : null;
            $password = isset($_POST['password']) ? $_POST['password'] : null;

            if ($username && $password) {
                loginUser($username, $password);
            } else {
                echo json_encode(['error' => 'Données manquantes pour la connexion']);
            }
            break;
        case 'checkout':
            checkout();
            break;
        case 'getUserAccountInfo':
            getUserAccountInfo();
            break;

        case 'getUserOrderHistory':
            getUserOrderHistory();
            break;

        case 'getUserOrderDetails':
            $orderId = isset($_GET['orderId']) ? intval($_GET['orderId']) : null;
            if ($orderId) {
                getUserOrderDetails($orderId);
            } else {
                echo json_encode(['error' => 'ID de la commande manquant']);
            }
            break;
        case 'reorder':
            $orderId = isset($_POST['orderId']) ? intval($_POST['orderId']) : null;
            if ($orderId) {
                reorder($orderId);
            } else {
                echo json_encode(['error' => 'ID de la commande manquant']);
            }
            break;
        
        case 'addOrderToCart':
            $orderId = isset($_POST['orderId']) ? intval($_POST['orderId']) : null;
            if ($orderId) {
                addOrderToCart($orderId);
            } else {
                echo json_encode(['error' => 'ID de la commande manquant']);
            }
            break;
        default:
            echo json_encode(['error' => 'Action non reconnue']);
            break;
    }
} else {
    echo json_encode(['error' => 'Aucune action spécifiée']);
}
?>
