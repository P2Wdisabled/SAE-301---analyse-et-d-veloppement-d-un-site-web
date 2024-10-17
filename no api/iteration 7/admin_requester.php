<?php
// admin_requester.php

require_once 'modele.php';

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'adminLogin':
            $username = isset($_POST['username']) ? $_POST['username'] : null;
            $password = isset($_POST['password']) ? $_POST['password'] : null;

            if ($username && $password) {
                adminLogin($username, $password);
            } else {
                echo json_encode(['error' => 'Données manquantes pour la connexion administrateur']);
            }
            break;
        case 'getAllOrders':
            getAllOrders();
            break;
        case 'updateOrderStatus':
            $orderId = isset($_POST['orderId']) ? intval($_POST['orderId']) : null;
            $status = isset($_POST['status']) ? $_POST['status'] : null;

            if ($orderId && $status) {
                try {
                    updateOrderStatus($orderId, $status);
                } catch (Exception $e) {
                    echo json_encode(['error' => $e->getMessage()]);
                }
            } else {
                echo json_encode(['error' => 'Données manquantes pour la mise à jour du statut']);
            }
            break;
        default:
            echo json_encode(['error' => 'Action non reconnue']);
            break;
    }
} else {
    echo json_encode(['error' => 'Aucune action spécifiée']);
}
?>
