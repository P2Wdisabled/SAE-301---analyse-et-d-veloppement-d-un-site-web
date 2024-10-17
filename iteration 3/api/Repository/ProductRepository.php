<?php
require_once("Repository/EntityRepository.php");
require_once("Class/Product.php");

/**
 * Classe ProductRepository
 * Gère les opérations CRUD pour les produits.
 */
class ProductRepository extends EntityRepository {

    public function __construct(){
        parent::__construct();
    }

    public function findAll(): array {
        $stmt = $this->cnx->prepare("SELECT * FROM products");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $res = [];
        foreach ($products as $prod) {
            $product = new Product($prod['id']);
            $product->setName($prod['name'])
                    ->setDescription($prod['description'])
                    ->setPrice(floatval($prod['price']))
                    ->setImageUrl($prod['image_url']);

            // Récupérer les catégories associées
            $stmt_cat = $this->cnx->prepare("
                SELECT category_id FROM product_categories WHERE product_id = :product_id
            ");
            $stmt_cat->bindParam(':product_id', $prod['id'], PDO::PARAM_INT);
            $stmt_cat->execute();
            $categories = $stmt_cat->fetchAll(PDO::FETCH_COLUMN, 0);
            $product->setCategories($categories);

            $res[] = $product;
        }
        return $res;
    }

    public function save($product): bool {
        try {
            $this->cnx->beginTransaction();

            // Insérer le produit
            $stmt = $this->cnx->prepare("
                INSERT INTO products (name, description, price, image_url)
                VALUES (:name, :description, :price, :image_url)
            ");
            $stmt->bindParam(':name', $product->getName(), PDO::PARAM_STR);
            $stmt->bindParam(':description', $product->getDescription(), PDO::PARAM_STR);
            $stmt->bindParam(':price', $product->getPrice());
            $stmt->bindParam(':image_url', $product->getImageUrl(), PDO::PARAM_STR);
            $stmt->execute();
            $product_id = $this->cnx->lastInsertId();
            $product->setId((int)$product_id);

            // Associer les catégories
            if (!empty($product->getCategories())) {
                $stmt_cat = $this->cnx->prepare("
                    INSERT INTO product_categories (product_id, category_id)
                    VALUES (:product_id, :category_id)
                ");
                foreach ($product->getCategories() as $cat_id) {
                    $stmt_cat->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                    $stmt_cat->bindParam(':category_id', $cat_id, PDO::PARAM_INT);
                    $stmt_cat->execute();
                }
            }

            $this->cnx->commit();
            return true;
        } catch (Exception $e) {
            $this->cnx->rollBack();
            return false;
        }
    }

    public function delete($id): bool {
        try {
            $this->cnx->beginTransaction();

            // Supprimer les associations avec les catégories
            $stmt_cat = $this->cnx->prepare("DELETE FROM product_categories WHERE product_id = :product_id");
            $stmt_cat->bindParam(':product_id', $id, PDO::PARAM_INT);
            $stmt_cat->execute();

            // Supprimer le produit
            $stmt = $this->cnx->prepare("DELETE FROM products WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->cnx->commit();
            return true;
        } catch (Exception $e) {
            $this->cnx->rollBack();
            return false;
        }
    }

    public function update($product): bool {
        try {
            $this->cnx->beginTransaction();

            // Mettre à jour le produit
            $stmt = $this->cnx->prepare("
                UPDATE products
                SET name = :name, description = :description, price = :price, image_url = :image_url
                WHERE id = :id
            ");
            $stmt->bindParam(':name', $product->getName(), PDO::PARAM_STR);
            $stmt->bindParam(':description', $product->getDescription(), PDO::PARAM_STR);
            $stmt->bindParam(':price', $product->getPrice());
            $stmt->bindParam(':image_url', $product->getImageUrl(), PDO::PARAM_STR);
            $stmt->bindParam(':id', $product->getId(), PDO::PARAM_INT);
            $stmt->execute();

            // Mettre à jour les catégories
            // Supprimer les anciennes associations
            $stmt_del_cat = $this->cnx->prepare("DELETE FROM product_categories WHERE product_id = :product_id");
            $stmt_del_cat->bindParam(':product_id', $product->getId(), PDO::PARAM_INT);
            $stmt_del_cat->execute();

            // Ajouter les nouvelles associations
            if (!empty($product->getCategories())) {
                $stmt_add_cat = $this->cnx->prepare("
                    INSERT INTO product_categories (product_id, category_id)
                    VALUES (:product_id, :category_id)
                ");
                foreach ($product->getCategories() as $cat_id) {
                    $stmt_add_cat->bindParam(':product_id', $product->getId(), PDO::PARAM_INT);
                    $stmt_add_cat->bindParam(':category_id', $cat_id, PDO::PARAM_INT);
                    $stmt_add_cat->execute();
                }
            }

            $this->cnx->commit();
            return true;
        } catch (Exception $e) {
            $this->cnx->rollBack();
            return false;
        }
    }
    public function find($id): ?Product{
        $stmt = $this->cnx->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $prod = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$prod) return null;
    
        $product = new Product($prod['id']);
        $product->setName($prod['name'])
                ->setDescription($prod['description'])
                ->setPrice(floatval($prod['price']))
                ->setImageUrl($prod['image_url']);
    
        // Récupérer les catégories associées
        $stmt_cat = $this->cnx->prepare("
            SELECT category_id FROM product_categories WHERE product_id = :product_id
        ");
        $stmt_cat->bindParam(':product_id', $id, PDO::PARAM_INT);
        $stmt_cat->execute();
        $categories = $stmt_cat->fetchAll(PDO::FETCH_COLUMN, 0);
        $product->setCategories($categories);
    
        // Récupérer les options associées
        $stmt_opt = $this->cnx->prepare("
            SELECT po.id, po.product_id, po.name, po.default_value
            FROM product_options po
            WHERE po.product_id = :product_id
        ");
        $stmt_opt->bindParam(':product_id', $id, PDO::PARAM_INT);
        $stmt_opt->execute();
        $options = $stmt_opt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($options as $opt) {
            $option = [
                "id" => $opt['id'],
                "product_id" => $opt['product_id'],
                "name" => $opt['name'],
                "default_value" => $opt['default_value']
            ];
    
            // Récupérer les valeurs de l'option
            $stmt_values = $this->cnx->prepare("
                SELECT id, product_option_id, value FROM option_values WHERE product_option_id = :option_id
            ");
            $stmt_values->bindParam(':option_id', $opt['id'], PDO::PARAM_INT);
            $stmt_values->execute();
            $values = $stmt_values->fetchAll(PDO::FETCH_ASSOC);
            $option['values'] = $values;
    
            $product->addOption($option);
        }
    
        return $product;
    }
    
}
?>
