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
        default:
            echo json_encode(['error' => 'Action non reconnue']);
            break;
    }
} else {
    echo json_encode(['error' => 'Aucune action spécifiée']);
}
?>
