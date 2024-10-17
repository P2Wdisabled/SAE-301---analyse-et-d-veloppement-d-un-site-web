<?php
// modele.php

// Fonction de connexion à la base de données
function getDBConnection() {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=potevin1;charset=utf8', 'votre_nom_utilisateur', 'votre_mot_de_passe');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

// Fonction pour récupérer tous les produits
function getAllProducts() {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM products");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products);
}
?>
