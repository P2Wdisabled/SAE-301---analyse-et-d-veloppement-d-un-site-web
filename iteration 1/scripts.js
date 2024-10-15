// Données des produits
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
            ] 
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
            ]
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
            ]
        }
    ]
};

// Fonction pour afficher les produits
function renderProducts(products) {
    let container = document.querySelector("#productsContainer");
    container.innerHTML = ''; // Vider le conteneur

    products.forEach(product => {
        let div = document.createElement("div");
        div.className = "product";

        let img = document.createElement("img");
        img.src = product.image;
        img.alt = product.name;

        let h2 = document.createElement("h2");
        h2.textContent = product.name;

        let p1 = document.createElement("p");
        p1.textContent = `Âge : ${product.age}, Pièces : ${product.pieces}`;

        let p2 = document.createElement("p");
        p2.textContent = `Prix : ${product.price.toFixed(2)} €`;

        div.appendChild(img);
        div.appendChild(h2);
        div.appendChild(p1);
        div.appendChild(p2);

        container.appendChild(div);
    });
}

// Afficher tous les produits au chargement de la page
renderProducts(data.products);
