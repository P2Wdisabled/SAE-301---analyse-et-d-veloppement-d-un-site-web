<?php
/**
 * Classe HttpRequest
 * Pour encapsuler toutes les informations utiles sur une requête HTTP.
 */
class HttpRequest {
    private string $method; // Méthode de la requête (GET, POST, DELETE, PATCH, PUT)
    private string $ressources = "none"; // Type de ressource ciblée, extrait de l'URI
    private string $id = ""; // Identifiant de la ressource
    private ?array $params = null; // Éventuels paramètres de la requête
    private string $json = ""; // Données JSON transmises par le client
    private bool $includeFiles = false;

    public function __construct(){
        $this->method = $_SERVER['REQUEST_METHOD'];

        $uri = $_SERVER["REQUEST_URI"];

        $tmp = explode("?", $uri); // Pour enlever les éventuels paramètres en GET
        $tmp = $tmp[0];
        $tmp = explode("/", $tmp);

        while( count($tmp)>1 && $tmp[1] != "api"){
            array_shift($tmp);
        }
    
        if (isset($tmp[1]) && $tmp[1]=="api" && count($tmp)>2){
            $this->ressources = $tmp[2];
            if (count($tmp)==4 && $tmp[3]!="")
                $this->id = $tmp[3];
        }
        $this->params = $_REQUEST;
        $this->json = file_get_contents("php://input"); // Lecture des données reçues au format JSON
        
        if ($this->method == "POST"){
            if (isset($_FILES) && count($_FILES)>0){
                foreach($_FILES as $key => $value){
                    if ($value['error'] == UPLOAD_ERR_OK){
                        $this->params[$key] = $value;
                        $this->includeFiles = true;
                    }
                }
            }
        }
    }

    public function getRessources()
    {
        return $this->ressources;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getParam(string $name)
    {
        if (isset($this->params[$name]) ){
            return $this->params[$name];
        }
        else {
            return false;
        }
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getJson()
    {
        return $this->json;
    }

    public function getIncludeFiles()
    {
        return $this->includeFiles;
    }
}
?>
