<?php
require_once "Controller.php";
require_once "Repository/OrderRepository.php";
require_once "Repository/OrderItemRepository.php";
require_once "Repository/CartRepository.php";
require_once "Repository/CartItemRepository.php";
require_once "Repository/ProductVariantRepository.php";


class OrderController extends Controller {

    private OrderRepository $orders;
    private OrderItemRepository $orderItems;
    private CartRepository $carts;
    private CartItemRepository $cartItems;
    private ProductVariantRepository $productVariants;

    public function __construct(){
        $this->orders = new OrderRepository();
        $this->orderItems = new OrderItemRepository();
        $this->carts = new CartRepository();
        $this->cartItems = new CartItemRepository();
        $this->productVariants = new ProductVariantRepository();
    }

    /**
     * Retrieves the authenticated user's ID from the token in headers or query parameters.
     * **Important:** This method is **INSECURE** and should only be used for testing.
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

        // Check query parameters if token not found in headers
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            if (is_numeric($token)) {
                return (int)$token;
            }
        }

        return null;
    }

    /**
     * Processes GET requests for orders.
     */
    protected function processGetRequest(HttpRequest $request) {
        $id = $request->getId();
        $user_id = $this->getAuthenticatedUserId();
        if (!$user_id) {
            http_response_code(401);
            return ["error" => "Unauthorized"];
        }

        if ($id) {
            // Retrieve a specific order for the user
            $order = $this->orders->find($id);
            if ($order == null || $order['user_id'] != $user_id) {
                http_response_code(404);
                return ["error" => "Order not found"];
            }

            $order_details = [
                "order_id" => $order['id'],
                "status" => $order['status'],
                "total_amount" => $order['total_amount'],
                "created_at" => $order['created_at'],
                "updated_at" => $order['updated_at'],
                "items" => $this->orderItems->findByOrderId($order['id'])
            ];
            return $order_details;
        } else {
            // Retrieve all orders for the user
            $orders = $this->orders->findByUserId($user_id);

            if (!$orders) {
                return ["message" => "No orders found"];
            }

            $result = [];
            foreach ($orders as $order) {
                $order_details = [
                    "order_id" => $order->getId(),
                    "status" => $order->getStatus(),
                    "total_amount" => $order->getTotalAmount(),
                    "created_at" => $order->getCreatedAt(),
                    "updated_at" => $order->getUpdatedAt(),
                    "items" => $this->orderItems->findByOrderId($order->getId())
                ];
                
                $result[] = $order_details;
            }

            return $result;
        }
    }

    /**
     * Processes POST requests for orders.
     */
    protected function processPostRequest(HttpRequest $request) {
        // For simplicity, we'll assume any POST request validates the cart
        return $this->validateCart($request);
    }

    /**
     * Processes DELETE requests (not implemented for orders).
     */
    protected function processDeleteRequest(HttpRequest $request) {
        http_response_code(405);
        return ["error" => "Method Not Allowed"];
    }

    /**
     * Processes PUT requests (not implemented for orders).
     */
    protected function processPutRequest(HttpRequest $request) {
        http_response_code(405);
        return ["error" => "Method Not Allowed"];
    }

    /**
     * Validates the cart and creates an order.
     */
    private function validateCart(HttpRequest $request) {
        $user_id = $this->getAuthenticatedUserId();
        if (!$user_id) {
            http_response_code(401);
            return ["error" => "Unauthorized"];
        }

        // Retrieve the user's cart
        $cart = $this->carts->findByUserId($user_id);
        if (!$cart) {
            http_response_code(400);
            return ["error" => "Cart not found"];
        }

        $cart_id = $cart['id'];

        // Retrieve the cart items
        $items = $this->cartItems->findByCartId($cart_id);
        if (empty($items)) {
            http_response_code(400);
            return ["error" => "Cart is empty"];
        }

        // Calculate the total amount
        $total_amount = 0;
        foreach ($items as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }

        // Begin a transaction
        try {
            $this->orders->beginTransaction();

            // Create the order
            $order = $this->orders->createOrder($user_id, $total_amount);
            if (!$order) {
                throw new Exception("Failed to create order");
            }

            $order_id = $order['id'];

            // Create the order items
            foreach ($items as $item) {
                // Vérifier le stock
                $variant = $this->productVariants->find($item['product_variant_id']);
                if (!$variant || $variant['stock_quantity'] < $item['quantity']) {
                    throw new Exception("Insufficient stock for product variant ID " . $item['product_variant_id']);
                }

                // Create the order item
                $ok = $this->orderItems->createOrderItem(
                    $order_id,
                    $item['product_variant_id'],
                    $item['quantity'],
                    $item['price']
                );

                if (!$ok) {
                    throw new Exception("Failed to create order item for product variant ID " . $item['product_variant_id']);
                }

                // Update stock
                $stock_ok = $this->productVariants->updateStock($item['product_variant_id'], -$item['quantity']);
            if (!$stock_ok) {
                throw new Exception("Failed to update stock for product variant ID " . $item['product_variant_id']);
            }
            }

            // Clear the cart: delete all cart items
            $this->cartItems->deleteByCartId($cart_id);

            // Commit the transaction
            $this->orders->commit();

            // Retrieve order details
            $order_details = [
                "order_id" => $order_id,
                "user_id" => $user_id,
                "status" => $order['status'],
                "total_amount" => $order['total_amount'],
                "items" => $this->orderItems->findByOrderId($order_id),
                "created_at" => $order['created_at'],
                "updated_at" => $order['updated_at']
            ];

            return $order_details;

        } catch (Exception $e) {
            // Roll back the transaction
            $this->orders->rollBack();
            http_response_code(500);
            return ["error" => $e->getMessage()];
        }
    }
}
?>
