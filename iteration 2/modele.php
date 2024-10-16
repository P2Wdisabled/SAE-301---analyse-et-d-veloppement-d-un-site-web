<?php
// modele.php

// Fonction de connexion (identique à l'itération 1)
function getDBConnection() {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=potevin1;charset=utf8', 'votre_nom_utilisateur', 'votre_mot_de_passe');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

// Fonction pour récupérer tous les produits, avec option de filtrage par catégorie
function getAllProducts($categoryId = null) {
    $pdo = getDBConnection();
    if ($categoryId) {
        $stmt = $pdo->prepare("
            SELECT p.* FROM products p
            INNER JOIN product_categories pc ON p.id = pc.product_id
            WHERE pc.category_id = :category_id
        ");
        $stmt->execute(['category_id' => $categoryId]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM products");
        $stmt->execute();
    }
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products);
}

// Fonction pour récupérer toutes les catégories
function getAllCategories() {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM categories");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($categories);
}
?>
