<?php
require_once("Repository/EntityRepository.php");
require_once("Class/Cart.php");
require_once("Repository/ProductVariantRepository.php");

/**
 * Classe CartItemRepository
 * Gère les opérations CRUD pour les items du panier.
 */
class CartItemRepository extends EntityRepository {

    private ProductVariantRepository $productVariants;
    public function __construct(){
        parent::__construct();
        $this->productVariants = new ProductVariantRepository();
    }

    /**
     * Trouve un item de panier par ID.
     */
    public function find($id): ?array{
        $stmt = $this->cnx->prepare("SELECT * FROM cart_items WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        return $item ? $item : null;
    }

    /**
     * Trouve tous les items de panier.
     */
    public function findAll(): array {
        $stmt = $this->cnx->prepare("SELECT * FROM cart_items");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Sauvegarde un nouvel item de panier.
     */
    public function save($item): bool {
        try {
            $stmt = $this->cnx->prepare("
                INSERT INTO cart_items (cart_id, product_id, quantity)
                VALUES (:cart_id, :product_id, :quantity)
            ");
            $stmt->bindParam(':cart_id', $item['cart_id'], PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Met à jour un item de panier existant.
     */
    public function update($item): bool {
        try {
            $stmt = $this->cnx->prepare("
                UPDATE cart_items
                SET cart_id = :cart_id, product_id = :product_id, quantity = :quantity
                WHERE id = :id
            ");
            $stmt->bindParam(':cart_id', $item['cart_id'], PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':id', $item['id'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Supprime un item de panier par ID.
     */
    public function delete($id): bool {
        try {
            $stmt = $this->cnx->prepare("DELETE FROM cart_items WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Récupère tous les items d'un panier spécifique.
     */
    public function findByCartId(int $cart_id): array {
        $stmt = $this->cnx->prepare("
            SELECT ci.id, ci.product_id, p.name, p.description, p.price, ci.quantity
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.cart_id = :cart_id
        ");
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Ajoute ou met à jour un item dans le panier.
     */
    public function addOrUpdateItem(int $cart_id, int $product_id, int $quantity): bool {
        try {
            // Vérifier si l'item existe déjà
            $stmt = $this->cnx->prepare("
                SELECT id, quantity FROM cart_items 
                WHERE cart_id = :cart_id AND product_id = :product_id
            ");
            $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->execute();
            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($item) {
                if ($quantity <= 0) {
                    // Supprimer l'item si la quantité est nulle ou négative
                    return $this->deleteItem($item['id']);
                } else {
                    // Mettre à jour la quantité
                    $stmt_update = $this->cnx->prepare("
                        UPDATE cart_items
                        SET quantity = :quantity, updated_at = NOW()
                        WHERE id = :id
                    ");
                    $stmt_update->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                    $stmt_update->bindParam(':id', $item['id'], PDO::PARAM_INT);
                    return $stmt_update->execute();
                }
            } else {
                if ($quantity <= 0) {
                    // Ne rien faire si la quantité est nulle ou négative
                    return false;
                }
                // Ajouter un nouvel item
                $stmt_insert = $this->cnx->prepare("
                    INSERT INTO cart_items (cart_id, product_id, quantity)
                    VALUES (:cart_id, :product_id, :quantity)
                ");
                $stmt_insert->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
                $stmt_insert->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                $stmt_insert->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                return $stmt_insert->execute();
            }
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Supprime un item du panier par ID.
     */
    public function deleteItem(int $id): bool {
        try {
            $stmt = $this->cnx->prepare("DELETE FROM cart_items WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Récupère tous les items d'un panier.
     */
    public function getItemsByCartId(int $cart_id): array {
        $stmt = $this->cnx->prepare("
            SELECT ci.product_id, p.name, p.description, p.price, ci.quantity
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.cart_id = :cart_id
        ");
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Supprime tous les items d'un panier.
     */
    public function clearCart(int $cart_id): bool {
        try {
            $stmt = $this->cnx->prepare("DELETE FROM cart_items WHERE cart_id = :cart_id");
            $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

        /**
     * Supprime tous les items d'un panier spécifique.
     */
    public function deleteByCartId(int $cart_id): bool {
        try {
            $stmt = $this->cnx->prepare("DELETE FROM cart_items WHERE cart_id = :cart_id");
            $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
    public function updateQuantity($cart_item_id, $quantity): bool {
        // Récupérer l'item du panier
        $cart_item = $this->find($cart_item_id);
        if (!$cart_item) return false;
    
        // Récupérer le stock du produit
        $variant = $this->productVariants->find($cart_item['product_variant_id']);
        if (!$variant) return false;
    
        if ($quantity > $variant['stock_quantity']) {
            return false; // Quantité demandée supérieure au stock disponible
        }
    
        $stmt = $this->cnx->prepare("
            UPDATE cart_items SET quantity = :quantity WHERE id = :id
        ");
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':id', $cart_item_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
}
?>
