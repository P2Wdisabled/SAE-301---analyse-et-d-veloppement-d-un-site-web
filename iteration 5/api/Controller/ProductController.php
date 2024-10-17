<?php
require_once "Controller.php";
require_once "Repository/ProductRepository.php";

/**
 * Classe ProductController
 * Gère les requêtes REST pour les produits.
 */
class ProductController extends Controller {

    private ProductRepository $products;

    public function __construct(){
        $this->products = new ProductRepository();
    }

    protected function processGetRequest(HttpRequest $request) {
        $id = $request->getId();
        $queryParams = $_GET;

        if ($id){
            // URI est .../products/{id}
            $p = $this->products->find($id);
            return $p == null ? false : $p;
        }
        else{
            // URI est .../products
            if (isset($queryParams['category'])) {
                $category_id = (int)$queryParams['category'];
                $products = $this->products->findAll();
                $filtered = [];
                foreach ($products as $prod) {
                    if (in_array($category_id, $prod->getCategories())) {
                        $filtered[] = $prod;
                    }
                }
                return $filtered;
            }
            return $this->products->findAll();
        }
    }

    protected function processPostRequest(HttpRequest $request) {
        $json = $request->getJson();
        $obj = json_decode($json, true);
        if (!$obj) return false;

        $p = new Product(0); // 0 est une valeur temporaire
        $p->setName($obj['name'] ?? '')
          ->setDescription($obj['description'] ?? '')
          ->setPrice(floatval($obj['price'] ?? 0))
          ->setImageUrl($obj['image_url'] ?? '');

        // Gestion des catégories (array)
        if (isset($obj['categories']) && is_array($obj['categories'])) {
            $p->setCategories($obj['categories']);
        }

        $ok = $this->products->save($p); 
        return $ok ? $p : false;
    }

    protected function processDeleteRequest(HttpRequest $request) {
        $id = $request->getId();
        if ($id){
            return $this->products->delete($id);
        }
        return false;
    }

    protected function processPutRequest(HttpRequest $request) {
        $id = $request->getId();
        if ($id){
            $json = $request->getJson();
            $obj = json_decode($json, true);
            if (!$obj) return false;

            $p = $this->products->find($id);
            if ($p == null) return false;

            $p->setName($obj['name'] ?? $p->getName())
              ->setDescription($obj['description'] ?? $p->getDescription())
              ->setPrice(floatval($obj['price'] ?? $p->getPrice()))
              ->setImageUrl($obj['image_url'] ?? $p->getImageUrl());

            // Gestion des catégories (array)
            if (isset($obj['categories']) && is_array($obj['categories'])) {
                $p->setCategories($obj['categories']);
            }

            return $this->products->update($p);
        }
        return false;
    }
}
?>
