<?php

require_once "Controller.php";
require_once "Repository/CartRepository.php";
require_once "Repository/CartItemRepository.php";
require_once "Repository/ProductVariantRepository.php";
require_once "Repository/ProductRepository.php";
require_once "Class/HttpRequest.php";

/**
 * Classe CartController
 * Gère les requêtes REST pour le panier d'achat anonyme.
 */
class CartController extends Controller {

    private CartRepository $carts;
    private CartItemRepository $cartItems;
    private ProductRepository $products;
    private ProductVariantRepository $productVariants;

    public function __construct(){
        $this->carts = new CartRepository();
        $this->cartItems = new CartItemRepository();
        $this->products = new ProductRepository();
        $this->productVariants = new ProductVariantRepository();
    }

    /**
     * Récupère le token du panier à partir de la requête.
     */
    private function getCartToken(HttpRequest $request): ?string {
        $params = $request->getParams();
        $cart_token = $params['cart_token'] ?? null;
        return $cart_token;
    }
    

    protected function processGetRequest(HttpRequest $request) {
        $cart_token = $this->getCartToken($request);
        if (!$cart_token) {
            // Créer un nouveau panier
            $cart = new Cart(0);
            $cart_token = bin2hex(random_bytes(16)); // Générer un token unique
            $cart->setToken($cart_token);
            $this->carts->save($cart);

            // Retourner le panier avec le token
            return [
                "message" => "New cart created",
                "cart_token" => $cart_token,
                "cart" => $cart
            ];
        }

        // Récupérer le panier par son token
        $cart = $this->carts->findByToken($cart_token);
        if (!$cart) {
            http_response_code(404);
            return ["error" => "Cart not found"];
        }

        return $cart;
    }

    protected function processPostRequest(HttpRequest $request) {
        $cart_token = $this->getCartToken($request);
        if (!$cart_token) {
            // Créer un nouveau panier
            $cart = new Cart(0);
            $cart_token = bin2hex(random_bytes(16));
            $cart->setToken($cart_token);
            $this->carts->save($cart);
        } else {
            // Récupérer le panier existant
            $cart = $this->carts->findByToken($cart_token);
            if (!$cart) {
                http_response_code(404);
                return ["error" => "Cart not found"];
            }
        }

        $json = $request->getJson();
        $data = json_decode($json, true);

        if (!$data) {
            http_response_code(400);
            return ["error" => "Invalid JSON"];
        }

        $product_variant_id = $data['product_variant_id'] ?? null;
        $quantity = $data['quantity'] ?? 1;

        if (!$product_variant_id) {
            http_response_code(400);
            return ["error" => "Product variant ID is required"];
        }

        // Vérifier le stock
        $variant = $this->productVariants->find($product_variant_id);
        if (!$variant) {
            http_response_code(404);
            return ["error" => "Product variant not found"];
        }

        if ($variant['stock_quantity'] == 0) {
            http_response_code(400);
            return ["error" => "Product is unavailable"];
        }

        if ($quantity > $variant['stock_quantity']) {
            http_response_code(400);
            return ["error" => "Insufficient stock"];
        }

        // Ajouter ou mettre à jour l'item dans le panier
        $ok = $this->cartItems->addOrUpdateItem(
            $cart->getId(), $product_variant_id, $quantity
        );
        if ($ok) {
            // Récupérer les items mis à jour
            $cart->setItems(
                $this->cartItems->findByCartId($cart->getId())
            );
            return [
                "cart_token" => $cart_token,
                "cart" => $cart
            ];
        } else {
            return ["error" => "Failed to add item to cart"];
        }
    }

    protected function processPutRequest(HttpRequest $request) {
        $cart_token = $this->getCartToken($request);
        if (!$cart_token) {
            http_response_code(400);
            return ["error" => "Cart token is required"];
        }

        $cart = $this->carts->findByToken($cart_token);
        if (!$cart) {
            http_response_code(404);
            return ["error" => "Cart not found"];
        }

        $json = $request->getJson();
        $data = json_decode($json, true);

        if (!$data) {
            http_response_code(400);
            return ["error" => "Invalid JSON"];
        }

        $product_variant_id = $data['product_variant_id'] ?? null;
        $quantity = $data['quantity'] ?? null;

        if (!$product_variant_id || $quantity === null) {
            http_response_code(400);
            return ["error" => "Product variant ID and quantity required"];
        }

        // Mettre à jour la quantité de l'item
        $ok = $this->cartItems->updateQuantityByCartAndProduct(
            $cart->getId(), $product_variant_id, $quantity
        );
        if ($ok) {
            // Récupérer les items mis à jour
            $cart->setItems(
                $this->cartItems->findByCartId($cart->getId())
            );
            return [
                "cart_token" => $cart_token,
                "cart" => $cart
            ];
        } else {
            http_response_code(400);
            return ["error" => "Failed to update item in cart"];
        }
    }

    protected function processDeleteRequest(HttpRequest $request) {
        $cart_token = $this->getCartToken($request);
        if (!$cart_token) {
            http_response_code(400);
            return ["error" => "Cart token is required"];
        }

        $cart = $this->carts->findByToken($cart_token);
        if (!$cart) {
            http_response_code(404);
            return ["error" => "Cart not found"];
        }

        $json = $request->getJson();
        $data = json_decode($json, true);

        if ($data && isset($data['product_variant_id'])) {
            $product_variant_id = $data['product_variant_id'];

            // Supprimer l'item du panier
            $ok = $this->cartItems->removeItem(
                $cart->getId(), $product_variant_id
            );
            if ($ok) {
                // Récupérer les items mis à jour
                $cart->setItems(
                    $this->cartItems->findByCartId($cart->getId())
                );
                return [
                    "cart_token" => $cart_token,
                    "cart" => $cart
                ];
            } else {
                http_response_code(400);
                return ["error" => "Failed to remove item from cart"];
            }
        } else {
            // Supprimer le panier entier
            $ok = $this->carts->delete($cart->getId());
            if ($ok) {
                return ["message" => "Cart deleted"];
            } else {
                http_response_code(400);
                return ["error" => "Failed to delete cart"];
            }
        }
    }
}
?>
