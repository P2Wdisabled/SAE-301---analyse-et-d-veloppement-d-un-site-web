<?php
require_once("Repository/EntityRepository.php");
require_once("Repository/ProductVariantRepository.php");

/**
 * Classe CartItemRepository
 * Gère les opérations CRUD pour les items du panier.
 */
class CartItemRepository extends EntityRepository {


    public function __construct(){
        parent::__construct();
    }

    /**
     * Trouve un item de panier par ID.
     */
    public function find($id): ?array {
        $stmt = $this->cnx->prepare("
            SELECT * FROM cart_items WHERE id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        return $item ?: null;
    }

    /**
     * Récupère tous les items de panier.
     */
    public function findAll(): array {
        $stmt = $this->cnx->prepare("
            SELECT ci.*, pv.price, pv.stock_quantity, 
                   p.name, p.description, p.image_url
            FROM cart_items ci
            JOIN product_variants pv ON ci.product_variant_id = pv.id
            JOIN products p ON pv.product_id = p.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Sauvegarde un nouvel item de panier.
     */
    public function save($item): bool {
        try {
            $stmt = $this->cnx->prepare("
                INSERT INTO cart_items (cart_id, product_variant_id, quantity)
                VALUES (:cart_id, :product_variant_id, :quantity)
            ");
            $stmt->bindParam(':cart_id', $item['cart_id'], PDO::PARAM_INT);
            $stmt->bindParam(':product_variant_id', $item['product_variant_id'], PDO::PARAM_INT);
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
                SET quantity = :quantity, updated_at = NOW()
                WHERE id = :id
            ");
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
            $stmt = $this->cnx->prepare("
                DELETE FROM cart_items WHERE id = :id
            ");
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
            SELECT ci.*, pv.price, pv.stock_quantity, 
                   p.name, p.description, p.image_url
            FROM cart_items ci
            JOIN product_variants pv ON ci.product_variant_id = pv.id
            JOIN products p ON pv.product_id = p.id
            WHERE ci.cart_id = :cart_id
        ");
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ajoute ou met à jour un item dans le panier.
     */
    public function addOrUpdateItem(
        int $cart_id, int $product_variant_id, int $quantity
    ): bool {
        try {
            // Vérifier si l'item existe déjà
            $stmt = $this->cnx->prepare("
                SELECT id, quantity FROM cart_items 
                WHERE cart_id = :cart_id AND 
                      product_variant_id = :product_variant_id
            ");
            $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
            $stmt->bindParam(
                ':product_variant_id', $product_variant_id, PDO::PARAM_INT
            );
            $stmt->execute();
            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($item) {
                if ($quantity <= 0) {
                    // Supprimer l'item si quantité nulle ou négative
                    return $this->delete($item['id']);
                } else {
                    // Mettre à jour la quantité
                    $stmt_update = $this->cnx->prepare("
                        UPDATE cart_items
                        SET quantity = :quantity, updated_at = NOW()
                        WHERE id = :id
                    ");
                    $stmt_update->bindParam(
                        ':quantity', $quantity, PDO::PARAM_INT
                    );
                    $stmt_update->bindParam(':id', $item['id'], PDO::PARAM_INT);
                    return $stmt_update->execute();
                }
            } else {
                if ($quantity <= 0) {
                    // Ne rien faire si quantité nulle ou négative
                    return false;
                }
                // Ajouter un nouvel item
                $stmt_insert = $this->cnx->prepare("
                    INSERT INTO cart_items 
                    (cart_id, product_variant_id, quantity)
                    VALUES (:cart_id, :product_variant_id, :quantity)
                ");
                $stmt_insert->bindParam(
                    ':cart_id', $cart_id, PDO::PARAM_INT
                );
                $stmt_insert->bindParam(
                    ':product_variant_id', $product_variant_id, PDO::PARAM_INT
                );
                $stmt_insert->bindParam(
                    ':quantity', $quantity, PDO::PARAM_INT
                );
                return $stmt_insert->execute();
            }
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Supprime un item du panier par cart_id et product_variant_id.
     */
    public function removeItem(
        int $cart_id, int $product_variant_id
    ): bool {
        try {
            $stmt = $this->cnx->prepare("
                DELETE FROM cart_items 
                WHERE cart_id = :cart_id AND 
                      product_variant_id = :product_variant_id
            ");
            $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
            $stmt->bindParam(
                ':product_variant_id', $product_variant_id, PDO::PARAM_INT
            );
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
            $stmt = $this->cnx->prepare("
                DELETE FROM cart_items WHERE cart_id = :cart_id
            ");
            $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Met à jour la quantité d'un item dans le panier.
     */
    public function updateQuantityByCartAndProduct(
        int $cart_id, int $product_variant_id, int $quantity
    ): bool {
        // Vérifier le stock
        $variant = $this->productVariants->find($product_variant_id);
        if (!$variant) return false;

        if ($quantity > $variant['stock_quantity']) {
            return false; // Quantité supérieure au stock disponible
        }

        $stmt = $this->cnx->prepare("
            UPDATE cart_items
            SET quantity = :quantity
            WHERE cart_id = :cart_id AND 
                  product_variant_id = :product_variant_id
        ");
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        $stmt->bindParam(
            ':product_variant_id', $product_variant_id, PDO::PARAM_INT
        );
        return $stmt->execute();
    }
/**
     * Trouve un panier par token.
     */
    public function findByToken(string $token): ?Cart {
        $stmt = $this->cnx->prepare("
            SELECT * FROM carts WHERE token = :token
        ");
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        $cartData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cartData) return null;

        $cart = new Cart($cartData['id']);
        $cart->setToken($cartData['token']);

        // Récupérer les items du panier
        $items = $this->findByCartId($cartData['id']);

        $cart->setItems($items);

        return $cart;
    }
}
?>
