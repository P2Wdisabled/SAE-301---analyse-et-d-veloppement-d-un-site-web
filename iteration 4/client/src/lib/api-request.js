/**
 *  Besoin de comprendre comment fonctionne fetch ?
 *  C'est ici : https://fr.javascript.info/fetch
 */

let API_URL = "http://localhost/iteration%201/api/";

/**
 *  getRequest
 * 
 *  Requête en GET de l'URI uri.
 *  ...
 */
let getRequest = async function(uri){

    let options = {
        method: "GET"
    };

    try{
        console.log(API_URL + uri);
        var response = await fetch(API_URL + uri, options); // exécution de la requête
    }
    catch(e){
        console.error("Échec de la requête : " + e);
        return false;
    }
    if (!response.ok){
        console.error("Erreur de requête : " + response.status);
        return false;
    }
    let $obj = await response.json(); // extraction du JSON de la réponse
    return $obj; // Retourne les données
}

/**
 *  postRequest
 * 
 *  Requête en POST de l'URI uri.
 *  ...
 */
let postRequest = async function(uri, data){

    let options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: data
    };

    try{
        var response = await fetch(API_URL + uri, options);
    }
    catch(e){
        console.error("Échec de la requête : " + e);
        return false;
    }
    if (!response.ok){
        console.error("Erreur de requête : " + response.status);
        return false;
    }
    let $obj = await response.json();
    return $obj;
}

/**
 *  deleteRequest
 * 
 *  Requête en DELETE de l'URI uri.
 *  ...
 */
let deleteRequest = async function(uri, data = null){

    let options = {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        }
    };

    if (data) {
        options.body = data;
    }

    try{
        var response = await fetch(API_URL + uri, options);
    }
    catch(e){
        console.error("Échec de la requête : " + e);
        return false;
    }
    if (!response.ok){
        console.error("Erreur de requête : " + response.status);
        return false;
    }
    return true;
}

/** 
 *  patchRequest
 * 
 *  Requête en PATCH de l'URI uri.
 *  ...
 */
let patchRequest = async function(uri, data){

    let options = {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json'
        },
        body: data
    };

    try{
        var response = await fetch(API_URL + uri, options);
    }
    catch(e){
        console.error("Échec de la requête : " + e);
        return false;
    }
    if (!response.ok){
        console.error("Erreur de requête : " + response.status);
        return false;
    }
    let $obj = await response.json();
    return $obj;
}

export { getRequest, postRequest, deleteRequest, patchRequest }
