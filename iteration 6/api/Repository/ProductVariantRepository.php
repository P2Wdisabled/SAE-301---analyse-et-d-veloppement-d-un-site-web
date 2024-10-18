<?php
require_once("Repository/EntityRepository.php");

/**
 * Classe ProductVariantRepository
 * Gère les opérations CRUD pour les variantes de produits.
 */
class ProductVariantRepository extends EntityRepository {

    public function __construct(){
        parent::__construct();
    }

    /**
     * Trouve une variante de produit par ID.
     */
    public function find($id): ?array {
        $stmt = $this->cnx->prepare("SELECT * FROM product_variants WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $variant = $stmt->fetch(PDO::FETCH_ASSOC);
        return $variant ? $variant : null;
    }

    /**
     * Trouve toutes les variantes de produits.
     */
    public function findAll(): array {
        $stmt = $this->cnx->prepare("SELECT * FROM product_variants");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Sauvegarde une variante de produit.
     */
    public function save($variant): bool {
        try {
            $stmt = $this->cnx->prepare("
                INSERT INTO product_variants (product_id, sku, price, stock_quantity)
                VALUES (:product_id, :sku, :price, :stock_quantity)
            ");
            $stmt->bindParam(':product_id', $variant['product_id'], PDO::PARAM_INT);
            $stmt->bindParam(':sku', $variant['sku'], PDO::PARAM_STR);
            $stmt->bindParam(':price', $variant['price']);
            $stmt->bindParam(':stock_quantity', $variant['stock_quantity'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Met à jour une variante de produit.
     */
    public function update($variant): bool {
        try {
            $stmt = $this->cnx->prepare("
                UPDATE product_variants
                SET product_id = :product_id, sku = :sku, price = :price, stock_quantity = :stock_quantity, updated_at = NOW()
                WHERE id = :id
            ");
            $stmt->bindParam(':product_id', $variant['product_id'], PDO::PARAM_INT);
            $stmt->bindParam(':sku', $variant['sku'], PDO::PARAM_STR);
            $stmt->bindParam(':price', $variant['price']);
            $stmt->bindParam(':stock_quantity', $variant['stock_quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':id', $variant['id'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Supprime une variante de produit par ID.
     */
    public function delete($id): bool {
        try {
            $stmt = $this->cnx->prepare("DELETE FROM product_variants WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Met à jour la quantité en stock d'une variante de produit.
     */
    public function updateStock($product_variant_id, $quantity_change): bool {
        $stmt = $this->cnx->prepare("
            UPDATE product_variants
            SET stock_quantity = stock_quantity + :quantity_change
            WHERE id = :id AND (stock_quantity + :quantity_change) >= 0
        ");
        $stmt->bindParam(':quantity_change', $quantity_change, PDO::PARAM_INT);
        $stmt->bindParam(':id', $product_variant_id, PDO::PARAM_INT);
        $stmt->execute();
    
        // Vérifier que la mise à jour a affecté une ligne
        return $stmt->rowCount() > 0;
    }
    
}
?>
