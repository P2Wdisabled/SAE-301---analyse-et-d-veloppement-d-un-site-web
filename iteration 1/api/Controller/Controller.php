<?php 
require_once("Class/HttpRequest.php");

/**
 * Classe abstraite Controller
 */
abstract class Controller {

     /**
     * jsonResponse
     * Traite la requête HTTP $request et renvoie une chaîne JSON en réponse
     * ou false si le traitement échoue.
     */
    public function jsonResponse(HttpRequest $request): ?string{
        $json = false;
        $method = $request->getMethod();
       
        // Selon la méthode, appelle la méthode protégée appropriée pour traiter la requête
        switch($method){
            case "GET":
                $data = $this->processGetRequest($request);
                break;
            
            case "POST":
                $data = $this->processPostRequest($request);
                break;

            case "DELETE":
                $data = $this->processDeleteRequest($request);
                break;

            case "PATCH":
                $data = $this->processPatchRequest($request);
                break;

            case "PUT":
                $data = $this->processPutRequest($request);
                break;

            default:
                $data = false;
                break;
        }

        if ($data) { $json = json_encode($data); }
        return $json;
    }

    protected function processGetRequest(HttpRequest $request){
        return ["warning" => "processGetRequest is not defined in " . static::class ];
    }

    protected function processPostRequest(HttpRequest $request){
        return ["warning" => "processPostRequest is not defined in " . static::class ];
    }

    protected function processDeleteRequest(HttpRequest $request){
        return ["warning" => "processDeleteRequest is not defined in " . static::class ];
    }

    protected function processPatchRequest(HttpRequest $request){
        return ["warning" => "processPatchRequest is not defined in " . static::class ];
    }

    protected function processPutRequest(HttpRequest $request){
        return ["warning" => "processPutRequest is not defined in " . static::class ];
    }
}
?>
