<?php
require_once("Repository/EntityRepository.php");
require_once("Class/Cart.php");

/**
 * Classe CartRepository
 * Gère les opérations CRUD pour les paniers.
 */
class CartRepository extends EntityRepository {

    public function __construct(){
        parent::__construct();
    }

    /**
     * Trouve un panier par ID.
     */
    public function find($id): ?Cart{
        $stmt = $this->cnx->prepare("SELECT * FROM carts WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $cartData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cartData) return null;

        $cart = new Cart($cartData['id']);
        $cart->setUserId($cartData['user_id']);

        // Récupérer les items du panier
        $stmt_items = $this->cnx->prepare("
            SELECT ci.product_variant_id, p.name, p.description, p.price, ci.quantity
            FROM cart_items ci
            JOIN products p ON ci.product_variant_id = p.id
            WHERE ci.cart_id = :cart_id
        ");
        $stmt_items->bindParam(':cart_id', $id, PDO::PARAM_INT);
        $stmt_items->execute();
        $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

        $cart->setItems($items);

        return $cart;
    }

    /**
     * Trouve un panier par user_id.
     * Si aucun panier n'existe, en crée un nouveau.
     */
    public function findByUserId(int $user_id): Cart {
        $stmt = $this->cnx->prepare("SELECT * FROM carts WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $cartData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cartData) {
            return $this->find($cartData['id']);
        } else {
            // Créer un nouveau panier
            $cart = new Cart(0);
            $cart->setUserId($user_id);
            $this->save($cart);
            return $this->find($cart->getId());
        }
    }

    /**
     * Récupère tous les paniers.
     */
    public function findAll(): array {
        $stmt = $this->cnx->prepare("SELECT * FROM carts");
        $stmt->execute();
        $cartsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $res = [];
        foreach ($cartsData as $cartData) {
            $cart = $this->find($cartData['id']);
            if ($cart !== null) {
                $res[] = $cart;
            }
        }
        return $res;
    }

    /**
     * Sauvegarde un panier dans la base de données.
     */
    public function save($cart): bool {
        try {
            $stmt = $this->cnx->prepare("
                INSERT INTO carts (user_id)
                VALUES (:user_id)
            ");
            $stmt->bindParam(':user_id', $cart->getUserId(), PDO::PARAM_INT);
            $stmt->execute();
            $cart_id = $this->cnx->lastInsertId();
            $cart->setId((int)$cart_id);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Met à jour un panier (actuellement, seule la gestion des items est réalisée via CartController).
     * Cette méthode peut être étendue si nécessaire.
     */
    public function update($cart): bool {
        try {
            $stmt = $this->cnx->prepare("
                UPDATE carts
                SET user_id = :user_id, updated_at = NOW()
                WHERE id = :id
            ");
            $stmt->bindParam(':user_id', $cart->getUserId(), PDO::PARAM_INT);
            $stmt->bindParam(':id', $cart->getId(), PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Supprime un panier par ID.
     */
    public function delete($id): bool {
        try {
            $stmt = $this->cnx->prepare("DELETE FROM carts WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
