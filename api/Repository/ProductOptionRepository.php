<?php
require_once("Repository/EntityRepository.php");
require_once("Class/ProductOption.php");

/**
 * Classe ProductOptionRepository
 * Gère les opérations CRUD pour les options de produit.
 */
class ProductOptionRepository extends EntityRepository {

    public function __construct(){
        parent::__construct();
    }

    public function find($id): ?ProductOption{
        $stmt = $this->cnx->prepare("SELECT * FROM product_options WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $opt = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$opt) return null;

        $option = new ProductOption($opt['id']);
        $option->setProductId($opt['product_id'])
               ->setName($opt['name'])
               ->setDefaultValue($opt['default_value']);

        return $option;
    }

    public function findAll(): array {
        $stmt = $this->cnx->prepare("SELECT * FROM product_options");
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $res = [];
        foreach ($options as $opt) {
            $option = new ProductOption($opt['id']);
            $option->setProductId($opt['product_id'])
                   ->setName($opt['name'])
                   ->setDefaultValue($opt['default_value']);
            $res[] = $option;
        }
        return $res;
    }

    public function findByProduct(int $product_id): array {
        $stmt = $this->cnx->prepare("SELECT * FROM product_options WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $res = [];
        foreach ($options as $opt) {
            $option = new ProductOption($opt['id']);
            $option->setProductId($opt['product_id'])
                   ->setName($opt['name'])
                   ->setDefaultValue($opt['default_value']);
            $res[] = $option;
        }
        return $res;
    }

    public function save($option): bool {
        try {
            $stmt = $this->cnx->prepare("
                INSERT INTO product_options (product_id, name, default_value)
                VALUES (:product_id, :name, :default_value)
            ");
            $stmt->bindParam(':product_id', $option->getProductId(), PDO::PARAM_INT);
            $stmt->bindParam(':name', $option->getName(), PDO::PARAM_STR);
            $stmt->bindParam(':default_value', $option->getDefaultValue(), PDO::PARAM_STR);
            $stmt->execute();
            $option->setId((int)$this->cnx->lastInsertId());
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($id): bool {
        try {
            $stmt = $this->cnx->prepare("DELETE FROM product_options WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function update($option): bool {
        try {
            $stmt = $this->cnx->prepare("
                UPDATE product_options
                SET product_id = :product_id, name = :name, default_value = :default_value
                WHERE id = :id
            ");
            $stmt->bindParam(':product_id', $option->getProductId(), PDO::PARAM_INT);
            $stmt->bindParam(':name', $option->getName(), PDO::PARAM_STR);
            $stmt->bindParam(':default_value', $option->getDefaultValue(), PDO::PARAM_STR);
            $stmt->bindParam(':id', $option->getId(), PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
