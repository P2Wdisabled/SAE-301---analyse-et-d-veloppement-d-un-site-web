<?php
require_once "Controller.php";
require_once "Repository/OrderRepository.php";

/**
 * Class AdminController
 * Gère les fonctionnalités du back office administrateur.
 */
class AdminController extends Controller {

    private OrderRepository $orders;

    public function __construct(){
        // Ne pas appeler parent::__construct() si la classe parente n'a pas de constructeur
        $this->orders = new OrderRepository();
    }

    /**
     * Récupère l'ID de l'utilisateur à partir du token dans les headers ou les paramètres de requête.
     * Vérifie si l'utilisateur est administrateur.
     */
    private function getAuthenticatedAdminId(): ?int {
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            $parts = explode(" ", $authHeader);
            if (count($parts) === 2 && $parts[0] === 'Bearer') {
                $token = $parts[1];
                if (is_numeric($token)) {
                    $user = $this->orders->findUserByToken($token);
                    if ($user && $user['is_admin'] == 1) {
                        return (int)$user['id'];
                    }
                }
            }
        }

        // Vérifier les paramètres de requête si le token n'est pas trouvé dans les headers
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            if (is_numeric($token)) {
                $user = $this->orders->findUserByToken($token);
                if ($user && $user['is_admin'] == 1) {
                    return (int)$user['id'];
                }
            }
        }

        return null;
    }

    /**
     * Traite les requêtes GET pour consulter les commandes.
     */
    protected function processGetRequest(HttpRequest $request) {
        $admin_id = $this->getAuthenticatedAdminId();
        if (!$admin_id) {
            http_response_code(401);
            return ["error" => "Unauthorized"];
        }

        // Récupérer toutes les commandes triées par date (du plus récent au plus ancien)
        $orders = $this->orders->findAllSortedByDate();

        if (!$orders) {
            return ["message" => "No orders found"];
        }

        $result = [];
        foreach ($orders as $order) {
            $order_details = [
                "order_id" => $order->getId(),
                "user_id" => $order->getUserId(),
                "status" => $order->getStatus(),
                "total_amount" => $order->getTotalAmount(),
                "created_at" => $order->getCreatedAt(),
                "updated_at" => $order->getUpdatedAt(),
                "items" => $this->orders->findOrderItemsByOrderId($order->getId())
            ];
            $result[] = $order_details;
        }

        return $result;
    }

    /**
     * Traite les requêtes PUT pour modifier le statut d'une commande.
     */
    protected function processPutRequest(HttpRequest $request) {
        $admin_id = $this->getAuthenticatedAdminId();
        if (!$admin_id) {
            http_response_code(401);
            return ["error" => "Unauthorized"];
        }

        $id = $request->getId();
        if (!$id) {
            http_response_code(400);
            return ["error" => "Order ID is required"];
        }

        $json = $request->getJson();
        $data = json_decode($json, true);

        $new_status = $data['status'] ?? null;
        if (!$new_status) {
            http_response_code(400);
            return ["error" => "New status is required"];
        }

        // Valider le nouveau statut
        $valid_statuses = ["en cours", "disponible", "annulée", "retirée"];
        if (!in_array($new_status, $valid_statuses)) {
            http_response_code(400);
            return ["error" => "Invalid status"];
        }

        // Mettre à jour le statut de la commande
        $order = $this->orders->find($id);
        if (!$order) {
            http_response_code(404);
            return ["error" => "Order not found"];
        }

        $current_status = $order->getStatus();

        // Si le statut ne change pas, ne rien faire
        if ($current_status === $new_status) {
            return ["message" => "Status unchanged"];
        }

        // Débuter une transaction
        try {
            $this->orders->beginTransaction();

            // Mettre à jour le statut
            $this->orders->updateStatus($id, $new_status);

            // Gérer la mise à jour du stock en fonction des statuts
            if ($new_status === "annulée") {
                // Annuler la commande : remettre le stock
                $this->orders->restockOrder($id);
            } elseif ($current_status === "annulée" && $new_status !== "annulée") {
                // Repasser une commande annulée à un autre statut : décrémenter le stock
                $this->orders->decrementStockOrder($id);
            }

            // Valider la transaction
            $this->orders->commit();

            return ["message" => "Order status updated successfully"];
        } catch (Exception $e) {
            // Annuler la transaction
            $this->orders->rollBack();
            http_response_code(500);
            return ["error" => "Failed to update order status: " . $e->getMessage()];
        }
    }

    /**
     * Traite les requêtes POST (non implémenté pour l'administrateur).
     */
    protected function processPostRequest(HttpRequest $request) {
        http_response_code(405);
        return ["error" => "Method Not Allowed"];
    }

    /**
     * Traite les requêtes DELETE (non implémenté pour l'administrateur).
     */
    protected function processDeleteRequest(HttpRequest $request) {
        http_response_code(405);
        return ["error" => "Method Not Allowed"];
    }
}
?>
