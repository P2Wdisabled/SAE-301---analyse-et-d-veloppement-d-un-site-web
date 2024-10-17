// scripts.js

// Fonction pour récupérer toutes les catégories
async function fetchAllCategories() {
    try {
        let response = await fetch('requester.php?action=getAllCategories');
        let categories = await response.json();
        return categories;
    } catch (error) {
        console.error('Erreur lors de la récupération des catégories :', error);
    }
}

// Fonction pour récupérer les produits, avec option de filtrage
async function fetchAllProducts(categoryId = null) {
    try {
        let url = 'requester.php?action=getAllProducts';
        if (categoryId) {
            url += `&categoryId=${categoryId}`;
        }
        let response = await fetch(url);
        let products = await response.json();
        return products;
    } catch (error) {
        console.error('Erreur lors de la récupération des produits :', error);
    }
}

// Fonction pour afficher les catégories dans un select
function renderCategories(categories) {
    let categorySelect = document.querySelector("#categorySelect");
    categorySelect.innerHTML = '';

    // Ajouter l'option "All"
    let allOption = document.createElement('option');
    allOption.value = '';
    allOption.textContent = 'All';
    categorySelect.appendChild(allOption);

    categories.forEach(category => {
        let option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name;
        categorySelect.appendChild(option);
    });
}

// Fonction pour afficher les produits
function renderProducts(products) {
    let container = document.querySelector("#productsContainer");
    container.innerHTML = '';

    if (products.length === 0) {
        container.textContent = 'Aucun produit trouvé pour la catégorie sélectionnée.';
        return;
    }

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
        p2.textContent = `Prix : ${parseFloat(product.price).toFixed(2)} €`;

        // Lien vers la page de détails du produit
        let detailsLink = document.createElement("a");
        detailsLink.href = `product.html?id=${product.id}`;
        detailsLink.textContent = "Voir les détails";

        div.appendChild(img);
        div.appendChild(h2);
        div.appendChild(p1);
        div.appendChild(p2);
        div.appendChild(detailsLink);

        container.appendChild(div);
    });
}

// Fonction pour récupérer les détails d'un produit
async function fetchProductDetails(productId) {
    try {
        let response = await fetch(`requester.php?action=getProductDetails&productId=${productId}`);
        let product = await response.json();
        return product;
    } catch (error) {
        console.error('Erreur lors de la récupération des détails du produit :', error);
    }
}

// Fonction pour afficher les détails du produit
function renderProductDetails(product) {
    let container = document.querySelector("#productDetail");
    container.innerHTML = '';

    if (product.error) {
        container.textContent = product.error;
        return;
    }

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
    p2.textContent = `Prix : ${parseFloat(product.price).toFixed(2)} €`;

    div.appendChild(img);
    div.appendChild(h2);
    div.appendChild(p1);
    div.appendChild(p2);

    // Afficher les options
    let optionsDiv = document.createElement("div");
    optionsDiv.className = "options";

    let selectedOptions = {};

    for (let optionName in product.options) {
        let optionValues = product.options[optionName];

        let label = document.createElement("label");
        label.textContent = `${optionName} : `;

        let select = document.createElement("select");
        optionValues.forEach(value => {
            let option = document.createElement("option");
            option.value = value;
            option.textContent = value;
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

    div.appendChild(optionsDiv);

    // Bouton "Ajouter au panier" (sera utilisé dans l'itération suivante)
    let addToCartButton = document.createElement("button");
    addToCartButton.textContent = "Ajouter au panier";
    addToCartButton.addEventListener("click", function() {
        // Fonctionnalité ajoutée dans l'itération 4
        alert("Fonctionnalité d'ajout au panier sera implémentée dans l'itération 4.");
    });

    div.appendChild(addToCartButton);

    container.appendChild(div);
}

// Gestion de l'affichage du détail du produit
async function displayProductDetail() {
    // Obtenir l'ID du produit depuis l'URL
    let params = new URLSearchParams(window.location.search);
    let productId = params.get('id');

    if (productId) {
        let product = await fetchProductDetails(productId);
        renderProductDetails(product);
    } else {
        document.querySelector("#productDetail").textContent = "ID du produit manquant dans l'URL.";
    }
}

// Chargement initial
document.addEventListener('DOMContentLoaded', async function() {
    if (document.querySelector("#categorySelect")) {
        let categories = await fetchAllCategories();
        renderCategories(categories);

        let products = await fetchAllProducts();
        renderProducts(products);

        // Écouteur pour le select des catégories
        document.querySelector('#categorySelect').addEventListener('change', async function() {
            let categoryId = this.value || null;
            let products = await fetchAllProducts(categoryId);
            renderProducts(products);
        });
    }

    if (document.querySelector("#productDetail")) {
        displayProductDetail();
    }
});
