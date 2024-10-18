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
        if ($id) {
            // Récupérer le produit avec le stock
            $product = $this->products->findWithStock($id);
            if ($product == null) return false;
    
            // Déterminer l'état du stock
            $stock_quantity = $product['stock_quantity'];
            $stock_status = 'disponible';
    
            if ($stock_quantity == 0) {
                $stock_status = 'temporairement indisponible';
            } elseif ($stock_quantity <= 5) { // Seuil fixé à 5
                $stock_status = 'bientôt épuisé';
            }
    
            $product['stock_status'] = $stock_status;
            $product['stock_quantity'] = $stock_quantity;
    
            return $product;
        } else {
            // Récupérer tous les produits avec leur stock
            $products = $this->products->findAllWithStock();
    
            // Ajouter l'état du stock pour chaque produit
            foreach ($products as &$product) {
                $stock_quantity = $product['stock_quantity'];
                $stock_status = 'disponible';
    
                if ($stock_quantity == 0) {
                    $stock_status = 'temporairement indisponible';
                } elseif ($stock_quantity <= 5) {
                    $stock_status = 'bientôt épuisé';
                }
    
                $product['stock_status'] = $stock_status;
            }
    
            return $products;
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
