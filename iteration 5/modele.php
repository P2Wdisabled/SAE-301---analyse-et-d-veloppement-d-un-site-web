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

// Fonction pour enregistrer un nouvel utilisateur
function registerUser($username, $password) {
    $pdo = getDBConnection();

    // Vérifier si l'utilisateur existe déjà
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode(['error' => 'Nom d\'utilisateur déjà pris']);
    } else {
        // Insérer le nouvel utilisateur
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->execute([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
        echo json_encode(['success' => 'Inscription réussie']);
    }
}

// Fonction pour connecter un utilisateur
function loginUser($username, $password) {
    $pdo = getDBConnection();

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        echo json_encode(['success' => 'Connexion réussie']);
    } else {
        echo json_encode(['error' => 'Nom d\'utilisateur ou mot de passe incorrect']);
    }
}

// Fonction pour valider le panier
function checkout() {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'Utilisateur non connecté']);
        return;
    }

    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo json_encode(['error' => 'Votre panier est vide']);
        return;
    }

    $pdo = getDBConnection();

    // Commencer une transaction
    $pdo->beginTransaction();

    try {
        // Insérer la commande
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, date) VALUES (:user_id, NOW())");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $orderId = $pdo->lastInsertId();

        // Insérer les articles de commande
        $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, options) VALUES (:order_id, :product_id, :quantity, :options)");

        foreach ($_SESSION['cart'] as $item) {
            $stmtItem->execute([
                'order_id' => $orderId,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'options' => json_encode($item['options'])
            ]);
        }

        // Vider le panier
        unset($_SESSION['cart']);

        // Valider la transaction
        $pdo->commit();

        echo json_encode(['success' => 'Commande validée avec succès']);
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollBack();
        echo json_encode(['error' => 'Erreur lors de la validation de la commande']);
    }
}
?>
