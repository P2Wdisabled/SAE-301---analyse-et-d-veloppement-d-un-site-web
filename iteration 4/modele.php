<?php
// modele.php

session_start();

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
            SELECT DISTINCT p.* FROM products p
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

// Fonction pour récupérer les détails d'un produit
function getProductDetails($productId) {
    $pdo = getDBConnection();

    // Récupérer les informations du produit
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :productId");
    $stmt->execute(['productId' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Récupérer les options du produit
        $stmtOptions = $pdo->prepare("
            SELECT o.name AS option_name, po.option_value
            FROM product_options po
            INNER JOIN options o ON po.option_id = o.id
            WHERE po.product_id = :productId
        ");
        $stmtOptions->execute(['productId' => $productId]);
        $options = $stmtOptions->fetchAll(PDO::FETCH_ASSOC);

        // Organiser les options
        $formattedOptions = [];
        foreach ($options as $option) {
            $optionName = $option['option_name'];
            $optionValue = $option['option_value'];
            if (!isset($formattedOptions[$optionName])) {
                $formattedOptions[$optionName] = [];
            }
            $formattedOptions[$optionName][] = $optionValue;
        }

        $product['options'] = $formattedOptions;
        echo json_encode($product);
    } else {
        echo json_encode(['error' => 'Produit non trouvé']);
    }
}

// Fonction pour ajouter un produit au panier
function addToCart($productId, $quantity, $options = []) {
    $pdo = getDBConnection();

    // Récupérer les détails du produit
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :productId");
    $stmt->execute(['productId' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $cartItem = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => $quantity,
            'options' => $options
        ];

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Vérifier si le produit avec les mêmes options existe déjà dans le panier
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $cartItem['id'] && $item['options'] == $cartItem['options']) {
                $item['quantity'] += $cartItem['quantity'];
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = $cartItem;
        }

        echo json_encode(['success' => 'Produit ajouté au panier']);
    } else {
        echo json_encode(['error' => 'Produit non trouvé']);
    }
}

// Fonction pour obtenir le panier
function getCart() {
    if (isset($_SESSION['cart'])) {
        echo json_encode($_SESSION['cart']);
    } else {
        echo json_encode([]);
    }
}

// Fonction pour mettre à jour la quantité d'un produit dans le panier
function updateCartItem($index, $quantity) {
    if (isset($_SESSION['cart'][$index])) {
        $_SESSION['cart'][$index]['quantity'] = $quantity;
        echo json_encode(['success' => 'Quantité mise à jour']);
    } else {
        echo json_encode(['error' => 'Article non trouvé dans le panier']);
    }
}

// Fonction pour supprimer un article du panier
function removeCartItem($index) {
    if (isset($_SESSION['cart'][$index])) {
        array_splice($_SESSION['cart'], $index, 1);
        echo json_encode(['success' => 'Article supprimé du panier']);
    } else {
        echo json_encode(['error' => 'Article non trouvé dans le panier']);
    }
}
?>
