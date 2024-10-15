// Données des produits (même que dans scripts.js)
let data = {
    "products": [
        {
            "id": 1,
            "name": "Super Mario World™ Mario et Yoshi",
            "image": "assets/SMWMY.jpg",
            "price": 19.99,
            "pieces": 1215,
            "age": "18+",
            "categories": [
                "LEGO® Super Mario™",
                "Fantastique",
                "Jeux vidéo"
            ],
            "options": {
                "couleurs": ["Rouge", "Vert", "Bleu"],
                "tailles": ["Petit", "Moyen", "Grand"]
            }
        },
        {
            "id": 2,
            "name": "Le vaisseau de transport impérial contre le speeder des éclaireurs rebelles",
            "image": "assets/VTICSER.jpg",
            "price": 29.99,
            "pieces": 383,
            "age": "8+",
            "categories": [
                "Star Wars™",
                "Fantastique"
            ],
            "options": {
                "versions": ["Standard", "Édition Collector"]
            }
        },
        {
            "id": 3,
            "name": "La couronne",
            "image": "assets/LC.jpg",
            "price": 9.99,
            "pieces": 1194,
            "age": "18+",
            "categories": [
                "LEGO® Icons",
                "Collection Botanique"
            ],
            "options": {
                "finition": ["Doré", "Argenté"],
                "emballage_cadeau": [true, false]
            }
        }
    ]
};

// Fonction pour obtenir les paramètres de l'URL
function getQueryParams() {
    let params = {};
    let queryString = window.location.search.substring(1);
    let vars = queryString.split("&");
    vars.forEach(v => {
        let pair = v.split("=");
        params[pair[0]] = decodeURIComponent(pair[1]);
    });
    return params;
}

// Obtenir l'ID du produit depuis l'URL
let params = getQueryParams();
let productId = parseInt(params.id);

// Trouver le produit correspondant
let product = data.products.find(p => p.id === productId);

if (product) {
    let container = document.querySelector("#productDetail");

    let div = document.createElement("div");
    div.className = "product-detail";

    let img = document.createElement("img");
    img.src = product.image;
    img.alt = product.name;

    let h2 = document.createElement("h2");
    h2.textContent = product.name;

    let p1 = document.createElement("p");
    p1.textContent = `Âge : ${product.age}, Pièces : ${product.pieces}`;

    let p2 = document.createElement("p");
    p2.textContent = `Prix : ${product.price.toFixed(2)} €`;

    // Afficher les options du produit (exemple simple)
    let optionDiv = document.createElement("div");
    optionDiv.className = "option";
    let optionLabel = document.createElement("label");
    optionLabel.textContent = "Quantité : ";
    let optionInput = document.createElement("input");
    optionInput.type = "number";
    optionInput.value = 1;
    optionInput.min = 1;
    optionInput.max = 5;
    optionLabel.appendChild(optionInput);
    optionDiv.appendChild(optionLabel);

    div.appendChild(img);
    div.appendChild(h2);
    div.appendChild(p1);
    div.appendChild(p2);
    div.appendChild(optionDiv);

    container.appendChild(div);
} else {
    document.querySelector("#productDetail").textContent = "Produit non trouvé.";
}
