<?php
require_once("Repository/EntityRepository.php");
require_once("Class/Cart.php");
require_once("Repository/CartItemRepository.php");

/**
 * Classe CartRepository
 * Gère les opérations CRUD pour les paniers.
 */
class CartRepository extends EntityRepository {

    private CartItemRepository $cartItems;

    public function __construct(){
        parent::__construct();
        $this->cartItems = new CartItemRepository();
    }

    /**
     * Trouve un panier par ID.
     */
    public function find($id): ?Cart {
        $stmt = $this->cnx->prepare("
            SELECT * FROM carts WHERE id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $cartData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cartData) return null;

        $cart = new Cart($cartData['id']);
        $cart->setToken($cartData['token']);

        // Récupérer les items du panier
        $items = $this->cartItems->findByCartId($id);

        $cart->setItems($items);

        return $cart;
    }

    /**
     * Trouve un panier par token.
     */
    public function findByToken(string $token): ?Cart {
        $stmt = $this->cnx->prepare("
            SELECT * FROM carts WHERE token = :token
        ");
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        $cartData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cartData) return null;

        $cart = new Cart($cartData['id']);
        $cart->setToken($cartData['token']);

        // Récupérer les items du panier
        $items = $this->cartItems->findByCartId($cartData['id']);

        $cart->setItems($items);

        return $cart;
    }

    /**
     * Sauvegarde un panier dans la base de données.
     */
    public function save($cart): bool {
        try {
            $stmt = $this->cnx->prepare("
                INSERT INTO carts (token)
                VALUES (:token)
            ");
            $token = $cart->getToken();
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->execute();
            $cart_id = $this->cnx->lastInsertId();
            $cart->setId((int)$cart_id);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
        

    /**
     * Met à jour un panier (actuellement non utilisé).
     */
    public function update($cart): bool {
        // Non utilisé pour l'instant
        return false;
    }

    /**
     * Supprime un panier par ID.
     */
    public function delete($id): bool {
        try {
            // Supprimer les items du panier
            $this->cartItems->deleteByCartId($id);

            // Supprimer le panier
            $stmt = $this->cnx->prepare("
                DELETE FROM carts WHERE id = :id
            ");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Récupère tous les paniers (si nécessaire).
     */
    public function findAll(): array {
        $stmt = $this->cnx->prepare("SELECT * FROM carts");
        $stmt->execute();
        $cartsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $carts = [];
        foreach ($cartsData as $cartData) {
            $cart = new Cart($cartData['id']);
            $cart->setToken($cartData['token']);
            $items = $this->cartItems->findByCartId($cartData['id']);
            $cart->setItems($items);
            $carts[] = $cart;
        }
        return $carts;
    }
}
?>
