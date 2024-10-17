<?php
require_once("Repository/EntityRepository.php");
require_once("Class/OrderItem.php");

/**
 * Classe OrderItemRepository
 * Gère les opérations CRUD pour les items des commandes.
 */
class OrderItemRepository extends EntityRepository {

    public function __construct(){
        parent::__construct();
    }

    /**
     * Trouve un item de commande par ID.
     */
    public function find($id): ?array {
        $stmt = $this->cnx->prepare("SELECT * FROM order_items WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        return $item ? $item : null;
    }

    /**
     * Trouve tous les items de commande.
     */
    public function findAll(): array {
        $stmt = $this->cnx->prepare("SELECT * FROM order_items");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Sauvegarde un nouvel item de commande.
     */
    public function save($orderItem): bool {
        try {
            $stmt = $this->cnx->prepare("
                INSERT INTO order_items (order_id, product_variant_id, quantity, unit_price, total_price)
                VALUES (:order_id, :product_variant_id, :quantity, :unit_price, :total_price)
            ");
            $stmt->bindParam(':order_id', $orderItem['order_id'], PDO::PARAM_INT);
            $stmt->bindParam(':product_variant_id', $orderItem['product_variant_id'], PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $orderItem['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':unit_price', $orderItem['unit_price']);
            $stmt->bindParam(':total_price', $orderItem['total_price']);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Met à jour un item de commande existant.
     */
    public function update($orderItem): bool {
        try {
            $stmt = $this->cnx->prepare("
                UPDATE order_items
                SET order_id = :order_id, product_variant_id = :product_variant_id, quantity = :quantity, unit_price = :unit_price, total_price = :total_price, updated_at = NOW()
                WHERE id = :id
            ");
            $stmt->bindParam(':order_id', $orderItem['order_id'], PDO::PARAM_INT);
            $stmt->bindParam(':product_variant_id', $orderItem['product_variant_id'], PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $orderItem['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':unit_price', $orderItem['unit_price']);
            $stmt->bindParam(':total_price', $orderItem['total_price']);
            $stmt->bindParam(':id', $orderItem['id'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Supprime un item de commande par ID.
     */
    public function delete($id): bool {
        try {
            $stmt = $this->cnx->prepare("DELETE FROM order_items WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Crée un nouvel item de commande.
     */
    public function createOrderItem(int $order_id, int $product_variant_id, int $quantity, float $unit_price): bool {
        try {
            $total_price = $quantity * $unit_price;
            $stmt = $this->cnx->prepare("
                INSERT INTO order_items (order_id, product_variant_id, quantity, unit_price, total_price)
                VALUES (:order_id, :product_variant_id, :quantity, :unit_price, :total_price)
            ");
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->bindParam(':product_variant_id', $product_variant_id, PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':unit_price', $unit_price);
            $stmt->bindParam(':total_price', $total_price);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Récupère tous les items d'une commande spécifique.
     */
    public function findByOrderId(int $order_id): array {
        $stmt = $this->cnx->prepare("
            SELECT oi.*, pv.sku, p.name
            FROM order_items oi
            JOIN product_variants pv ON oi.product_variant_id = pv.id
            JOIN products p ON pv.product_id = p.id
            WHERE oi.order_id = :order_id
        ");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findByCartId(int $cart_id): array {
        $stmt = $this->cnx->prepare("
            SELECT ci.*, pv.sku, pv.price, pv.stock_quantity, p.name
            FROM cart_items ci
            JOIN product_variants pv ON ci.product_variant_id = pv.id
            JOIN products p ON pv.product_id = p.id
            WHERE ci.cart_id = :cart_id
        ");
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>
