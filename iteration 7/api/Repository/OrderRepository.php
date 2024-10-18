<?php
require_once("Repository/EntityRepository.php");
require_once("Class/Order.php");

/**
 * Classe OrderRepository
 * Gère les opérations CRUD pour les commandes.
 */
class OrderRepository extends EntityRepository {

    public function __construct(){
        parent::__construct();
    }
    public function findUserByToken($token): ?array {
        $stmt = $this->cnx->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $token, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    /**
     * Récupère toutes les commandes triées par date (du plus récent au plus ancien).
     */
    public function findAllSortedByDate(): array {
        $stmt = $this->cnx->prepare("SELECT * FROM orders ORDER BY created_at DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $orders = [];
        foreach ($rows as $row) {
            $orders[] = new Order($row); // Passer le tableau associatif au constructeur
        }
        return $orders;
    }
    
    
    
    

    /**
     * Récupère les items d'une commande par son ID.
     */
    public function findOrderItemsByOrderId($order_id): array {
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

    /**
     * Met à jour le statut d'une commande.
     */
    public function updateStatus($order_id, $new_status): bool {
        $stmt = $this->cnx->prepare("
            UPDATE orders
            SET status = :status
            WHERE id = :id
        ");
        $stmt->bindParam(':status', $new_status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $order_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Met à jour le stock en annulant une commande (remettre le stock).
     */
    public function restockOrder($order_id): bool {
        // Récupérer les items de la commande
        $items = $this->findOrderItemsByOrderId($order_id);
        foreach ($items as $item) {
            $stmt = $this->cnx->prepare("
                UPDATE product_variants
                SET stock_quantity = stock_quantity + :quantity
                WHERE id = :variant_id
            ");
            $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':variant_id', $item['product_variant_id'], PDO::PARAM_INT);
            if (!$stmt->execute()) {
                return false;
            }
        }
        return true;
    }

    /**
     * Met à jour le stock en retirant une commande annulée précédemment (décrémenter le stock).
     */
    public function decrementStockOrder($order_id): bool {
        // Récupérer les items de la commande
        $items = $this->findOrderItemsByOrderId($order_id);
        foreach ($items as $item) {
            $stmt = $this->cnx->prepare("
                UPDATE product_variants
                SET stock_quantity = stock_quantity - :quantity
                WHERE id = :variant_id AND stock_quantity >= :quantity
            ");
            $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':variant_id', $item['product_variant_id'], PDO::PARAM_INT);
            if (!$stmt->execute()) {
                return false;
            }
        }
        return true;
    }
    /**
     * Trouve une commande par ID.
     */
    public function find($id): ?Order {
        $stmt = $this->cnx->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Order($row); // Passer le tableau associatif $row
        }
        return null;
    }
    
    
    
    public function getConnection(): PDO {
        return $this->cnx;
    }
    /**
     * Trouve toutes les commandes.
     */
    public function findAll(): array {
        $stmt = $this->cnx->prepare("SELECT * FROM orders");
        $stmt->execute();
        $orders_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $orders = [];
        foreach ($orders_data as $ord) {
            $orders[] = new Order($ord); // Passer le tableau associatif au constructeur
        }
        return $orders;
    }
    

    /**
     * Sauvegarde une commande.
     * Cette méthode peut être utilisée pour mettre à jour le statut ou d'autres détails de la commande.
     */
    public function save($order): bool {
        try {
            $stmt = $this->cnx->prepare("
                INSERT INTO orders (user_id, status, total_amount)
                VALUES (:user_id, :status, :total_amount)
            ");
            $stmt->bindParam(':user_id', $order->getUserId(), PDO::PARAM_INT);
            $stmt->bindParam(':status', $order->getStatus(), PDO::PARAM_STR);
            $stmt->bindParam(':total_amount', $order->getTotalAmount());
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Supprime une commande par ID.
     */
    public function delete($id): bool {
        try {
            $stmt = $this->cnx->prepare("DELETE FROM orders WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Met à jour une commande.
     */
    public function update($order): bool {
        try {
            $stmt = $this->cnx->prepare("
                UPDATE orders
                SET user_id = :user_id, status = :status, total_amount = :total_amount, updated_at = NOW()
                WHERE id = :id
            ");
            $stmt->bindParam(':user_id', $order->getUserId(), PDO::PARAM_INT);
            $stmt->bindParam(':status', $order->getStatus(), PDO::PARAM_STR);
            $stmt->bindParam(':total_amount', $order->getTotalAmount());
            $stmt->bindParam(':id', $order->getId(), PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Crée une nouvelle commande.
     */
    public function createOrder(int $user_id, float $total_amount): ?Order {
        try {
            $stmt = $this->cnx->prepare("
                INSERT INTO orders (user_id, status, total_amount)
                VALUES (:user_id, 'en cours', :total_amount)
            ");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':total_amount', $total_amount);
            $stmt->execute();
            $order_id = (int)$this->cnx->lastInsertId();
    
            // Récupérer les informations complètes de la commande nouvellement créée
            $stmt = $this->cnx->prepare("SELECT * FROM orders WHERE id = :id");
            $stmt->bindParam(':id', $order_id, PDO::PARAM_INT);
            $stmt->execute();
            $order_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Créer une instance de Order avec les données complètes
            $order = new Order($order_data);
            return $order;
        } catch (Exception $e) {
            return null;
        }
    }
    

    /**
     * Récupère toutes les commandes d'un utilisateur.
     */
    public function findByUserId(int $user_id): array {
        $stmt = $this->cnx->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $orders_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $orders = [];
        foreach ($orders_data as $ord) {
            $orders[] = new Order($ord); // Passer le tableau associatif au constructeur
        }
        return $orders;
    }
    
    public function beginTransaction() {
        $this->cnx->beginTransaction();
    }

    public function commit() {
        $this->cnx->commit();
    }

    public function rollBack() {
        $this->cnx->rollBack();
    }
}
?>
