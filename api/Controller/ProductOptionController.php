<?php
require_once "Controller.php";
require_once "Repository/ProductOptionRepository.php";
require_once "Repository/OptionValueRepository.php";
require_once "Class/ProductOption.php";
require_once "Class/OptionValue.php";

/**
 * Classe ProductOptionController
 * Gère les requêtes REST pour les options de produit.
 */
class ProductOptionController extends Controller {

    private ProductOptionRepository $options;
    private OptionValueRepository $values;

    public function __construct(){
        $this->options = new ProductOptionRepository();
        $this->values = new OptionValueRepository();
    }

    protected function processGetRequest(HttpRequest $request) {
        $id = $request->getId();
        if ($id){
            // URI est .../product_options/{id}
            $option = $this->options->find($id);
            if ($option == null) return false;

            // Récupérer les valeurs associées
            $values = $this->values->findByOption($id);
            $optionData = $option->jsonSerialize();
            $optionData['values'] = $values;
            return $optionData;
        }
        else{
            // URI est .../product_options
            return $this->options->findAll();
        }
    }

    protected function processPostRequest(HttpRequest $request) {
        $json = $request->getJson();
        $obj = json_decode($json, true);
        if (!$obj) return false;

        $option = new ProductOption(0); // 0 est une valeur temporaire
        $option->setProductId($obj['product_id'] ?? 0)
               ->setName($obj['name'] ?? '')
               ->setDefaultValue($obj['default_value'] ?? null);
        $ok = $this->options->save($option); 

        if ($ok && isset($obj['values']) && is_array($obj['values'])){
            foreach($obj['values'] as $val){
                $value = new OptionValue(0);
                $value->setProductOptionId($option->getId())
                      ->setValue($val);
                $this->values->save($value);
            }
        }

        return $ok ? $option : false;
    }

    protected function processDeleteRequest(HttpRequest $request) {
        $id = $request->getId();
        if ($id){
            return $this->options->delete($id);
        }
        return false;
    }

    protected function processPutRequest(HttpRequest $request) {
        $id = $request->getId();
        if ($id){
            $json = $request->getJson();
            $obj = json_decode($json, true);
            if (!$obj) return false;

            $option = $this->options->find($id);
            if ($option == null) return false;

            $option->setProductId($obj['product_id'] ?? $option->getProductId())
                   ->setName($obj['name'] ?? $option->getName())
                   ->setDefaultValue($obj['default_value'] ?? $option->getDefaultValue());

            $ok = $this->options->update($option);

            if ($ok && isset($obj['values']) && is_array($obj['values'])){
                // Supprimer les anciennes valeurs
                $existingValues = $this->values->findByOption($id);
                foreach($existingValues as $val){
                    $this->values->delete($val->getId());
                }
                // Ajouter les nouvelles valeurs
                foreach($obj['values'] as $val){
                    $value = new OptionValue(0);
                    $value->setProductOptionId($id)
                          ->setValue($val);
                    $this->values->save($value);
                }
            }

            return $ok;
        }
        return false;
    }
}
?>
