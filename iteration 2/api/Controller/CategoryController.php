<?php
require_once "Controller.php";
require_once "Repository/CategoryRepository.php";

/**
 * Classe CategoryController
 * Gère les requêtes REST pour les catégories.
 */
class CategoryController extends Controller {

    private CategoryRepository $categories;

    public function __construct(){
        $this->categories = new CategoryRepository();
    }

    protected function processGetRequest(HttpRequest $request) {
        $id = $request->getId();
        if ($id){
            // URI est .../categories/{id}
            $c = $this->categories->find($id);
            return $c == null ? false : $c;
        }
        else{
            // URI est .../categories
            return $this->categories->findAll();
        }
    }

    protected function processPostRequest(HttpRequest $request) {
        $json = $request->getJson();
        $obj = json_decode($json, true);
        if (!$obj) return false;

        $c = new Category(0); // 0 est une valeur temporaire
        $c->setName($obj['name'] ?? '')
          ->setDescription($obj['description'] ?? '');
        $ok = $this->categories->save($c); 
        return $ok ? $c : false;
    }

    protected function processDeleteRequest(HttpRequest $request) {
        $id = $request->getId();
        if ($id){
            return $this->categories->delete($id);
        }
        return false;
    }

    protected function processPutRequest(HttpRequest $request) {
        $id = $request->getId();
        if ($id){
            $json = $request->getJson();
            $obj = json_decode($json, true);
            if (!$obj) return false;

            $c = $this->categories->find($id);
            if ($c == null) return false;

            $c->setName($obj['name'] ?? $c->getName())
              ->setDescription($obj['description'] ?? $c->getDescription());
            return $this->categories->update($c);
        }
        return false;
    }
}
?>
