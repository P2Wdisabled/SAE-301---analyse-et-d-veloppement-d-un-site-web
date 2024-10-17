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
        // Vérifier le stock disponible
        if ($product['stock'] <= 0) {
            echo json_encode(['error' => 'Produit en rupture de stock']);
            return;
        }
        if ($quantity > $product['stock']) {
            echo json_encode(['error' => 'Quantité demandée supérieure au stock disponible']);
            return;
        }

        $cartItem = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => $quantity,
            'options' => $options,
            'stock' => $product['stock']
        ];

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Vérifier si le produit avec les mêmes options existe déjà dans le panier
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $cartItem['id'] && $item['options'] == $cartItem['options']) {
                $newQuantity = $item['quantity'] + $cartItem['quantity'];
                if ($newQuantity > $product['stock']) {
                    echo json_encode(['error' => 'Quantité totale demandée supérieure au stock disponible']);
                    return;
                }
                $item['quantity'] = $newQuantity;
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
        $productId = $_SESSION['cart'][$index]['id'];
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = :productId");
        $stmt->execute(['productId' => $productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($quantity > $product['stock']) {
            echo json_encode(['error' => 'Quantité demandée supérieure au stock disponible']);
            return;
        }

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
        // Vérifier le stock pour chaque produit
        foreach ($_SESSION['cart'] as $item) {
            $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = :productId");
            $stmt->execute(['productId' => $item['id']]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($item['quantity'] > $product['stock']) {
                throw new Exception('Stock insuffisant pour le produit ' . $item['name']);
            }
        }

        // Insérer la commande
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, date) VALUES (:user_id, NOW())");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $orderId = $pdo->lastInsertId();

        // Insérer les articles de commande et mettre à jour le stock
        $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, options) VALUES (:order_id, :product_id, :quantity, :options)");
        $stmtUpdateStock = $pdo->prepare("UPDATE products SET stock = stock - :quantity WHERE id = :productId");

        foreach ($_SESSION['cart'] as $item) {
            $stmtItem->execute([
                'order_id' => $orderId,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'options' => json_encode($item['options'])
            ]);

            // Mettre à jour le stock
            $stmtUpdateStock->execute([
                'quantity' => $item['quantity'],
                'productId' => $item['id']
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
        echo json_encode(['error' => 'Erreur lors de la validation de la commande : ' . $e->getMessage()]);
    }
}

// Fonction pour connecter un administrateur
function adminLogin($username, $password) {
    $pdo = getDBConnection();

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND role = 'admin'");
    $stmt->execute(['username' => $username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        echo json_encode(['success' => 'Connexion administrateur réussie']);
    } else {
        echo json_encode(['error' => 'Nom d\'utilisateur ou mot de passe incorrect']);
    }
}

// Fonction pour vérifier si un administrateur est connecté
function isAdminAuthenticated() {
    return isset($_SESSION['admin_id']);
}

// Fonction pour obtenir toutes les commandes
function getAllOrders() {
    if (!isAdminAuthenticated()) {
        echo json_encode(['error' => 'Accès non autorisé']);
        return;
    }

    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT o.*, u.username FROM orders o INNER JOIN users u ON o.user_id = u.id ORDER BY o.date DESC");
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($orders);
}

// Fonction pour mettre à jour le statut d'une commande
function updateOrderStatus($orderId, $status) {
    if (!isAdminAuthenticated()) {
        echo json_encode(['error' => 'Accès non autorisé']);
        return;
    }

    $pdo = getDBConnection();

    // Vérifier si la commande existe
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = :orderId");
    $stmt->execute(['orderId' => $orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo json_encode(['error' => 'Commande non trouvée']);
        return;
    }

    // Si le statut change à "annulée" ou depuis "annulée", mettre à jour le stock
    if ($order['status'] != $status) {
        if ($status == 'annulée') {
            // Remboursement du stock
            refundOrderStock($orderId);
        } elseif ($order['status'] == 'annulée') {
            // Déduction du stock
            deductOrderStock($orderId);
        }
    }

    // Mettre à jour le statut
    $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :orderId");
    $stmt->execute(['status' => $status, 'orderId' => $orderId]);

    echo json_encode(['success' => 'Statut de la commande mis à jour']);
}

// Fonction pour rembourser le stock d'une commande annulée
function refundOrderStock($orderId) {
    $pdo = getDBConnection();

    // Récupérer les articles de la commande
    $stmt = $pdo->prepare("SELECT product_id, quantity FROM order_items WHERE order_id = :orderId");
    $stmt->execute(['orderId' => $orderId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Mettre à jour le stock
    foreach ($items as $item) {
        $stmtUpdateStock = $pdo->prepare("UPDATE products SET stock = stock + :quantity WHERE id = :productId");
        $stmtUpdateStock->execute([
            'quantity' => $item['quantity'],
            'productId' => $item['product_id']
        ]);
    }
}

// Fonction pour déduire le stock si la commande est remise en cours
function deductOrderStock($orderId) {
    $pdo = getDBConnection();

    // Récupérer les articles de la commande
    $stmt = $pdo->prepare("SELECT product_id, quantity FROM order_items WHERE order_id = :orderId");
    $stmt->execute(['orderId' => $orderId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Vérifier le stock disponible
    foreach ($items as $item) {
        $stmtStock = $pdo->prepare("SELECT stock FROM products WHERE id = :productId");
        $stmtStock->execute(['productId' => $item['product_id']]);
        $product = $stmtStock->fetch(PDO::FETCH_ASSOC);

        if ($product['stock'] < $item['quantity']) {
            throw new Exception('Stock insuffisant pour le produit ID ' . $item['product_id']);
        }
    }

    // Mettre à jour le stock
    foreach ($items as $item) {
        $stmtUpdateStock = $pdo->prepare("UPDATE products SET stock = stock - :quantity WHERE id = :productId");
        $stmtUpdateStock->execute([
            'quantity' => $item['quantity'],
            'productId' => $item['product_id']
        ]);
    }
}

// Fonction pour récupérer les détails d'une commande
function getOrderDetails($orderId) {
    if (!isAdminAuthenticated()) {
        echo json_encode(['error' => 'Accès non autorisé']);
        return;
    }

    $pdo = getDBConnection();

    // Récupérer la commande
    $stmtOrder = $pdo->prepare("SELECT o.*, u.username FROM orders o INNER JOIN users u ON o.user_id = u.id WHERE o.id = :orderId");
    $stmtOrder->execute(['orderId' => $orderId]);
    $order = $stmtOrder->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo json_encode(['error' => 'Commande non trouvée']);
        return;
    }

    // Récupérer les articles de la commande
    $stmtItems = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi INNER JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :orderId");
    $stmtItems->execute(['orderId' => $orderId]);
    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

    $order['items'] = $items;

    echo json_encode($order);
}

// Fonction pour mettre à jour la quantité d'un article dans une commande
function updateOrderItem($orderItemId, $quantity) {
    if (!isAdminAuthenticated()) {
        echo json_encode(['error' => 'Accès non autorisé']);
        return;
    }

    $pdo = getDBConnection();

    // Récupérer l'article de commande
    $stmtItem = $pdo->prepare("SELECT * FROM order_items WHERE id = :orderItemId");
    $stmtItem->execute(['orderItemId' => $orderItemId]);
    $orderItem = $stmtItem->fetch(PDO::FETCH_ASSOC);

    if (!$orderItem) {
        echo json_encode(['error' => 'Article de commande non trouvé']);
        return;
    }

    // Récupérer la commande associée
    $stmtOrder = $pdo->prepare("SELECT * FROM orders WHERE id = :orderId");
    $stmtOrder->execute(['orderId' => $orderItem['order_id']]);
    $order = $stmtOrder->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo json_encode(['error' => 'Commande non trouvée']);
        return;
    }

    // Commencer une transaction
    $pdo->beginTransaction();

    try {
        // Calculer la différence de quantité
        $difference = $quantity - $orderItem['quantity'];

        if ($difference != 0) {
            // Mettre à jour le stock du produit
            $stmtProduct = $pdo->prepare("SELECT stock FROM products WHERE id = :productId");
            $stmtProduct->execute(['productId' => $orderItem['product_id']]);
            $product = $stmtProduct->fetch(PDO::FETCH_ASSOC);

            if ($difference > 0 && $product['stock'] < $difference) {
                throw new Exception('Stock insuffisant pour le produit ID ' . $orderItem['product_id']);
            }

            $stmtUpdateStock = $pdo->prepare("UPDATE products SET stock = stock - :difference WHERE id = :productId");
            $stmtUpdateStock->execute([
                'difference' => $difference,
                'productId' => $orderItem['product_id']
            ]);
        }

        // Mettre à jour la quantité de l'article de commande
        $stmtUpdateItem = $pdo->prepare("UPDATE order_items SET quantity = :quantity WHERE id = :orderItemId");
        $stmtUpdateItem->execute([
            'quantity' => $quantity,
            'orderItemId' => $orderItemId
        ]);

        // Valider la transaction
        $pdo->commit();

        echo json_encode(['success' => 'Quantité de l\'article mise à jour']);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['error' => $e->getMessage()]);
    }
}

?>
