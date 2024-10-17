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

        return $product;
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
            $res[] = $product;
        }
        return $res;
    }

    public function save($product): bool {
        try {
            $stmt = $this->cnx->prepare("
                INSERT INTO products (name, description, price, image_url)
                VALUES (:name, :description, :price, :image_url)
            ");
            $stmt->bindParam(':name', $product->getName(), PDO::PARAM_STR);
            $stmt->bindParam(':description', $product->getDescription(), PDO::PARAM_STR);
            $stmt->bindParam(':price', $product->getPrice());
            $stmt->bindParam(':image_url', $product->getImageUrl(), PDO::PARAM_STR);
            $stmt->execute();
            $product->setId((int)$this->cnx->lastInsertId());
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($id): bool {
        try {
            $stmt = $this->cnx->prepare("DELETE FROM products WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function update($product): bool {
        try {
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
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
