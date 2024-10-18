<?php
require_once("Repository/EntityRepository.php");
require_once("Class/OptionValue.php");

/**
 * Classe OptionValueRepository
 * Gère les opérations CRUD pour les valeurs d'option de produit.
 */
class OptionValueRepository extends EntityRepository {

    public function __construct(){
        parent::__construct();
    }

    public function find($id): ?OptionValue{
        $stmt = $this->cnx->prepare("SELECT * FROM option_values WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $val = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$val) return null;

        $value = new OptionValue($val['id']);
        $value->setProductOptionId($val['product_option_id'])
              ->setValue($val['value']);

        return $value;
    }

    public function findAll(): array {
        $stmt = $this->cnx->prepare("SELECT * FROM option_values");
        $stmt->execute();
        $values = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $res = [];
        foreach ($values as $val) {
            $value = new OptionValue($val['id']);
            $value->setProductOptionId($val['product_option_id'])
                  ->setValue($val['value']);
            $res[] = $value;
        }
        return $res;
    }

    public function findByOption(int $product_option_id): array {
        $stmt = $this->cnx->prepare("SELECT * FROM option_values WHERE product_option_id = :option_id");
        $stmt->bindParam(':option_id', $product_option_id, PDO::PARAM_INT);
        $stmt->execute();
        $values = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $res = [];
        foreach ($values as $val) {
            $value = new OptionValue($val['id']);
            $value->setProductOptionId($val['product_option_id'])
                  ->setValue($val['value']);
            $res[] = $value;
        }
        return $res;
    }

    public function save($value): bool {
        try {
            $stmt = $this->cnx->prepare("
                INSERT INTO option_values (product_option_id, value)
                VALUES (:product_option_id, :value)
            ");
            $stmt->bindParam(':product_option_id', $value->getProductOptionId(), PDO::PARAM_INT);
            $stmt->bindParam(':value', $value->getValue(), PDO::PARAM_STR);
            $stmt->execute();
            $value->setId((int)$this->cnx->lastInsertId());
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($id): bool {
        try {
            $stmt = $this->cnx->prepare("DELETE FROM option_values WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function update($value): bool {
        try {
            $stmt = $this->cnx->prepare("
                UPDATE option_values
                SET product_option_id = :product_option_id, value = :value
                WHERE id = :id
            ");
            $stmt->bindParam(':product_option_id', $value->getProductOptionId(), PDO::PARAM_INT);
            $stmt->bindParam(':value', $value->getValue(), PDO::PARAM_STR);
            $stmt->bindParam(':id', $value->getId(), PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
