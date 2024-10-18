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

    /**
     * Trouve une commande par ID.
     */
    public function find($id): ?Order {
        $stmt = $this->cnx->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $ord = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$ord) return null;

        $order = new Order($ord['id']);
        $order->setUserId($ord['user_id'])
              ->setStatus($ord['status'])
              ->setTotalAmount(floatval($ord['total_amount']))
              ->setCreatedAt($ord['created_at'])
              ->setUpdatedAt($ord['updated_at']);

        return $order;
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
            $order = new Order($ord['id']);
            $order->setUserId($ord['user_id'])
                  ->setStatus($ord['status'])
                  ->setTotalAmount(floatval($ord['total_amount']))
                  ->setCreatedAt($ord['created_at'])
                  ->setUpdatedAt($ord['updated_at']);
            $orders[] = $order;
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

            // Créer une instance de Order
            $order = new Order($order_id);
            $order->setUserId($user_id)
                  ->setStatus('en cours')
                  ->setTotalAmount($total_amount);
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
            $order = new Order($ord['id']);
            $order->setUserId($ord['user_id'])
                  ->setStatus($ord['status'])
                  ->setTotalAmount(floatval($ord['total_amount']))
                  ->setCreatedAt($ord['created_at'])
                  ->setUpdatedAt($ord['updated_at']);
            $orders[] = $order;
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
