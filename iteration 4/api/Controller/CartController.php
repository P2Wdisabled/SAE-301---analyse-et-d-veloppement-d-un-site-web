<?php
require_once "Controller.php";
require_once "Repository/CartRepository.php";
require_once "Repository/CartItemRepository.php";
require_once "Repository/ProductRepository.php";
require_once "Class/Cart.php";

/**
 * Classe CartController
 * Gère les requêtes REST pour le panier d'achat.
 */
class CartController extends Controller {

    private CartRepository $carts;
    private CartItemRepository $cartItems;
    private ProductRepository $products;

    public function __construct(){
        $this->carts = new CartRepository();
        $this->cartItems = new CartItemRepository();
        $this->products = new ProductRepository();
    }

    /**
     * Récupère l'ID de l'utilisateur à partir du token dans les headers ou les paramètres de requête.
     * Pour simplifier, nous supposons que le token est l'ID de l'utilisateur en clair.
     * 
     * **Important :** Cette méthode est **INSECURE** et doit être utilisée uniquement pour des tests.
     */
    private function getAuthenticatedUserId(): ?int {
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            $parts = explode(" ", $authHeader);
            if (count($parts) === 2 && $parts[0] === 'Bearer') {
                $token = $parts[1];
                if (is_numeric($token)) {
                    return (int)$token;
                }
            }
        }

        // Si le token n'est pas trouvé dans les headers, vérifier dans les paramètres de requête
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            if (is_numeric($token)) {
                return (int)$token;
            }
        }

        return null;
    }

    protected function processGetRequest(HttpRequest $request) {
        $user_id = $this->getAuthenticatedUserId();
        if (!$user_id) {
            http_response_code(401);
            return ["error" => "Unauthorized"];
        }

        // Récupérer le panier de l'utilisateur
        $cart = $this->carts->findByUserId($user_id);
        return $cart;
    }

    protected function processPostRequest(HttpRequest $request) {
        $user_id = $this->getAuthenticatedUserId();
        if (!$user_id) {
            http_response_code(401);
            return ["error" => "Unauthorized"];
        }

        $json = $request->getJson();
        $obj = json_decode($json, true);
        if (!$obj) return ["error" => "Invalid JSON"];

        // Ajouter un produit au panier
        if (isset($obj['add_item'])) {
            $product_id = $obj['product_id'] ?? 0;
            $quantity = $obj['quantity'] ?? 1;

            if ($product_id == 0 || $quantity <= 0) {
                return ["error" => "Invalid product_id or quantity"];
            }

            // Vérifier si le produit existe
            $product = $this->products->find($product_id);
            if ($product == null) {
                return ["error" => "Product not found"];
            }

            // Récupérer le panier de l'utilisateur
            $cart = $this->carts->findByUserId($user_id);

            // Ajouter ou mettre à jour l'item dans le panier
            $ok = $this->cartItems->addOrUpdateItem($cart->getId(), $product_id, $quantity);
            if ($ok) {
                // Récupérer les items mis à jour
                $cart->setItems($this->cartItems->findByCartId($cart->getId()));
                return $cart;
            } else {
                return ["error" => "Failed to add item to cart"];
            }
        }

        return ["error" => "Invalid request"];
    }

    protected function processDeleteRequest(HttpRequest $request) {
        $user_id = $this->getAuthenticatedUserId();
        if (!$user_id) {
            http_response_code(401);
            return ["error" => "Unauthorized"];
        }

        $json = $request->getJson();
        $obj = json_decode($json, true);
        if (!$obj) return ["error" => "Invalid JSON"];

        // Supprimer un produit du panier
        if (isset($obj['remove_item'])) {
            $product_id = $obj['product_id'] ?? 0;

            if ($product_id == 0) {
                return ["error" => "Invalid product_id"];
            }

            // Récupérer le panier de l'utilisateur
            $cart = $this->carts->findByUserId($user_id);

            // Supprimer l'item du panier en mettant la quantité à 0
            $ok = $this->cartItems->addOrUpdateItem($cart->getId(), $product_id, 0);
            if ($ok) {
                // Récupérer les items mis à jour
                $cart->setItems($this->cartItems->findByCartId($cart->getId()));
                return $cart;
            } else {
                return ["error" => "Failed to remove item from cart"];
            }
        }

        return ["error" => "Invalid request"];
    }

    protected function processPutRequest(HttpRequest $request) {
        $user_id = $this->getAuthenticatedUserId();
        if (!$user_id) {
            http_response_code(401);
            return ["error" => "Unauthorized"];
        }

        $json = $request->getJson();
        $obj = json_decode($json, true);
        if (!$obj) return ["error" => "Invalid JSON"];

        // Modifier la quantité d'un produit dans le panier
        if (isset($obj['update_item'])) {
            $product_id = $obj['product_id'] ?? 0;
            $quantity = $obj['quantity'] ?? 1;

            if ($product_id == 0 || $quantity < 0) {
                return ["error" => "Invalid product_id or quantity"];
            }

            // Récupérer le panier de l'utilisateur
            $cart = $this->carts->findByUserId($user_id);

            // Mettre à jour l'item dans le panier
            $ok = $this->cartItems->addOrUpdateItem($cart->getId(), $product_id, $quantity);
            if ($ok) {
                // Récupérer les items mis à jour
                $cart->setItems($this->cartItems->findByCartId($cart->getId()));
                return $cart;
            } else {
                return ["error" => "Failed to update item in cart"];
            }
        }

        return ["error" => "Invalid request"];
    }
}
?>
