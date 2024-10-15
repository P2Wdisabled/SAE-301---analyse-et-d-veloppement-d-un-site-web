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
    let optionsDiv = document.createElement("div");
    optionsDiv.className = "options";

    let selectedOptions = {};

    for (let optionName in product.options) {
        let optionValues = product.options[optionName];
        let label = document.createElement("label");
        label.textContent = `${optionName.charAt(0).toUpperCase() + optionName.slice(1)} : `;

        let select = document.createElement("select");
        optionValues.forEach(value => {
            let option = document.createElement("option");
            option.value = value;
            option.textContent = value.toString();
            select.appendChild(option);
        });

        select.addEventListener("change", function() {
            selectedOptions[optionName] = this.value;
        });

        // Sélection par défaut
        selectedOptions[optionName] = optionValues[0];

        label.appendChild(select);
        optionsDiv.appendChild(label);
        optionsDiv.appendChild(document.createElement("br"));
    }

    // Sélection de la quantité
    let quantityLabel = document.createElement("label");
    quantityLabel.textContent = "Quantité : ";
    let quantityInput = document.createElement("input");
    quantityInput.type = "number";
    quantityInput.value = 1;
    quantityInput.min = 1;
    quantityInput.max = 5
    quantityLabel.appendChild(quantityInput);
    optionsDiv.appendChild(quantityLabel);

    div.appendChild(optionsDiv);

    // Bouton "Ajouter au panier"
    let addToCartButton = document.createElement("button");
    addToCartButton.textContent = "Ajouter au panier";
    addToCartButton.addEventListener("click", function() {
        let quantity = parseInt(quantityInput.value);
        addToCart(product, quantity, selectedOptions);
        alert("Produit ajouté au panier !");
    });

    div.appendChild(addToCartButton);

    container.appendChild(div);
}

// Fonction pour ajouter un produit au panier
function addToCart(product, quantity, options) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let existingProduct = cart.find(item => item.id === product.id && JSON.stringify(item.options) === JSON.stringify(options));

    if (existingProduct) {
        existingProduct.quantity += quantity;
    } else {
        cart.push({
            id: product.id,
            name: product.name,
            price: product.price,
            image: product.image,
            quantity: quantity,
            options: options
        });
    }

    localStorage.setItem("cart", JSON.stringify(cart));
}