/*
    utils.js

    Ce fichier contient une fonction "outil" qui vous permet de charger
    des fichiers HTML contenant des templates de composants.

    Ce fichier est un module JS, on peut le repérer par l'instruction 'export' en
    fin de fichier. Cela signifie que les fonctions loadTemplate et loadJSON peuvent
    être importées dans d'autres fichiers/modules JS pour être utilisées.
*/


/*
    Note : async et await

    Les fonctions loadTemplate et loadJSON sont déclarées avec le mot-clé 'async'.
    A notre niveau, nous n'avons pas besoin de comprendre en détail ce que cela signifie.
    Pour faire simple, ces 2 fonctions vont lire un fichier. Et ce n'est pas une opération
    instantanée, cela prend un peu de temps, même si ce n'est que quelques millisecondes.
    Il faut donc dire au navigateur s'attendre (await) que la lecture soit terminée avant
    de poursuivre l'exécution du code. Sinon on risque de vouloir manipuler des données...
    qui ne sont pas encore chargées.

    Donc en gros, on dit au navigateur : "attend un peu, je vais lire un fichier, et quand
    j'ai fini, tu pourras continuer à exécuter le code qui suit".

    Pour pouvoir utiliser le mot-clé 'await', il faut que la fonction soit déclarée avec
    le mot-clé 'async'. C'est tout.

*/

/*  loadTemplate
    @param filename: string, le chemin vers le fichier HTML à charger
    @return string, le contenu du fichier HTML (sous forme de chaîne de caractères)
*/
let loadTemplate = async function(filename) {
    // fetch est une fonction JS qui permet de lire un fichier
    // il faut attendre (await) que la lecture soit terminée avant de poursuivre
    // et d'être certain d'avoir le résultat de l'appel à fetch dans response
    let response = await fetch(filename);
    // response est un objet JS qui contient le résultat de la lecture du fichier
    // response.text() est une fonction qui permet de récupérer le contenu du fichier sous forme de chaîne de caractères
    // c'est aussi une opération qui n'est pas instantanée, il faut donc attendre (await) que l'extraction soit terminée
    let html = await response.text();
    // on retourne le contenu du fichier HTML sous forme de chaîne de caractères
    return html;
}


// on exporte la fonction pour pouvoir les utiliser dans d'autres modules JS
export { loadTemplate};