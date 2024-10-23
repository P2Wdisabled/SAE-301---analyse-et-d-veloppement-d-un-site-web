<?php
require_once("Repository/EntityRepository.php");
require_once("Class/Category.php");

/**
 * Classe CategoryRepository
 * Gère les opérations CRUD pour les catégories.
 */
class CategoryRepository extends EntityRepository {

    public function __construct(){
        parent::__construct();
    }

    public function find($id): ?Category{
        $stmt = $this->cnx->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $cat = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt_prods = $this->cnx->prepare("SELECT * FROM product_categories JOIN products WHERE category_id = :category_id AND product_id = id");
        $stmt_prods->bindParam(':category_id', $id, PDO::PARAM_INT);
        $stmt_prods->execute();
        $cat['produits'] = $stmt_prods->fetchAll(PDO::FETCH_ASSOC);

        if (!$cat) return null;

        $category = new Category($cat['id']);
        $category->setName($cat['name'])
                 ->setDescription($cat['description'])
                 ->setProducts($cat['produits']);

        return $category;
    }

    public function findAll(): array {
        $stmt = $this->cnx->prepare("SELECT * FROM categories");
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $res = [];
        foreach ($categories as $cat) {
            $category = new Category($cat['id']);
            $category->setName($cat['name'])
                     ->setDescription($cat['description']);
            $res[] = $category;
        }
        return $res;
    }

    public function save($category): bool {
        try {
            $stmt = $this->cnx->prepare("
                INSERT INTO categories (name, description)
                VALUES (:name, :description)
            ");
            $stmt->bindParam(':name', $category->getName(), PDO::PARAM_STR);
            $stmt->bindParam(':description', $category->getDescription(), PDO::PARAM_STR);
            $stmt->execute();
            $category->setId((int)$this->cnx->lastInsertId());
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($id): bool {
        try {
            // Supprimer les associations dans product_categories
            $stmt_del_assoc = $this->cnx->prepare("DELETE FROM product_categories WHERE category_id = :category_id");
            $stmt_del_assoc->bindParam(':category_id', $id, PDO::PARAM_INT);
            $stmt_del_assoc->execute();

            // Supprimer la catégorie
            $stmt = $this->cnx->prepare("DELETE FROM categories WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function update($category): bool {
        try {
            $stmt = $this->cnx->prepare("
                UPDATE categories
                SET name = :name, description = :description
                WHERE id = :id
            ");
            $stmt->bindParam(':name', $category->getName(), PDO::PARAM_STR);
            $stmt->bindParam(':description', $category->getDescription(), PDO::PARAM_STR);
            $stmt->bindParam(':id', $category->getId(), PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
