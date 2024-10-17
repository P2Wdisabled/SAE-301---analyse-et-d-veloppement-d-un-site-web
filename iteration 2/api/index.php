<?php
require_once "Class/HttpRequest.php";
require_once "Controller/ProductController.php";
require_once "Controller/CategoryController.php";

/**
 * $router est notre routeur rudimentaire.
 * Il associe chaque ressource à son contrôleur.
 */
$router = [
    "products" => new ProductController(),
    "categories" => new CategoryController(),
];

// Objet HttpRequest qui contient toutes les infos utiles sur la requête
$request = new HttpRequest();

// Gestion des requêtes preflight (CORS)
if ($request->getMethod() == "OPTIONS"){
    http_response_code(200);
    exit();
}

// On récupère la ressource ciblée par la requête
$route = $request->getRessources();

if ( isset($router[$route]) ){ // Si on a un contrôleur pour cette ressource
    $ctrl = $router[$route];  // On le récupère
    $json = $ctrl->jsonResponse($request); // On invoque jsonResponse pour obtenir la réponse JSON
    if ($json){ 
        header("Content-type: application/json;charset=utf-8");
        echo $json;
    }
    else{
        http_response_code(404); // En cas de problème pour produire la réponse, on retourne un 404
    }
    die();
}
http_response_code(404); // Si on n'a pas de contrôleur pour traiter la requête -> 404
die();
?>
