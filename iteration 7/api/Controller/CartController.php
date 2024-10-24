<?php

require_once "Controller.php";
require_once "Repository/CartRepository.php";
require_once "Repository/CartItemRepository.php";
require_once "Repository/ProductVariantRepository.php";
require_once "Repository/ProductRepository.php";


/**
 * Classe CartController
 * Gère les requêtes REST pour le panier d'achat.
 */

 class CartController extends Controller {

    // Méthode pour récupérer un panier par ID (GET)
    public function getCart($id) {
        $cart = $this->carts->find($id);
        if ($cart) {
            return $cart;
        } else {
            return ['error' => 'Cart not found'];
        }
    }

    // Méthode pour créer un nouveau panier (POST)
    public function createCart() {
        $data = json_decode(file_get_contents("php://input"), true);
        $cart = new Cart($data['user_id']);

        if ($this->carts->save($cart)) {
            return ['message' => 'Cart created successfully'];
        } else {
            return ['error' => 'Failed to create cart'];
        }
    }

    // Méthode pour mettre à jour un panier (PUT)
    public function updateCart($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        $cart = $this->carts->find($id);

        if ($cart) {
            $cart->setUserId($data['user_id']); // Met à jour des informations

            if ($this->carts->update($cart)) {
                return ['message' => 'Cart updated successfully'];
            } else {
                return ['error' => 'Failed to update cart'];
            }
        } else {
            return ['error' => 'Cart not found'];
        }
    }

    // Méthode pour supprimer un panier (DELETE)
    public function deleteCart($id) {
        if ($this->carts->delete($id)) {
            return ['message' => 'Cart deleted successfully'];
        } else {
            return ['error' => 'Failed to delete cart'];
        }
    }
    
}
?>
