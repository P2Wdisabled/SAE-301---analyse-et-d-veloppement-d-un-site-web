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

    var selectedOptions = {};

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

    // Bouton "Ajouter au panier"
    let addToCartButton = document.createElement("button");
    addToCartButton.textContent = "Ajouter au panier";
    addToCartButton.addEventListener("click", async function() {
        let quantity = 1; // Vous pouvez ajouter un champ pour la quantité si vous le souhaitez
        let result = await addToCart(product.id, quantity, selectedOptions);
        if (result.success) {
            alert(result.success);
        } else {
            alert(result.error);
        }
    });

    div.appendChild(addToCartButton);

    container.appendChild(div);
}

// Fonction pour ajouter un produit au panier
async function addToCart(productId, quantity, options) {
    try {
        let formData = new FormData();
        formData.append('productId', productId);
        formData.append('quantity', quantity);
        formData.append('options', JSON.stringify(options));

        let response = await fetch('requester.php?action=addToCart', {
            method: 'POST',
            body: formData
        });
        let result = await response.json();
        return result;
    } catch (error) {
        console.error('Erreur lors de l\'ajout au panier :', error);
    }
}

// Fonction pour obtenir le panier
async function fetchCart() {
    try {
        let response = await fetch('requester.php?action=getCart');
        let cart = await response.json();
        return cart;
    } catch (error) {
        console.error('Erreur lors de la récupération du panier :', error);
    }
}

// Fonction pour afficher le panier
function renderCart(cart) {
    let container = document.querySelector("#cartContainer");
    container.innerHTML = '';

    if (cart.length === 0) {
        container.textContent = "Votre panier est vide.";
        return;
    }

    let table = document.createElement("table");
    let thead = document.createElement("thead");
    let headerRow = document.createElement("tr");

    ["Produit", "Options", "Prix unitaire", "Quantité", "Sous-total", "Actions"].forEach(text => {
        let th = document.createElement("th");
        th.textContent = text;
        headerRow.appendChild(th);
    });

    thead.appendChild(headerRow);
    table.appendChild(thead);

    let tbody = document.createElement("tbody");
    let total = 0;

    cart.forEach((item, index) => {
        let row = document.createElement("tr");

        // Produit
        let tdProduct = document.createElement("td");
        tdProduct.textContent = item.name;
        row.appendChild(tdProduct);

        // Options
        let tdOptions = document.createElement("td");
        if (item.options && Object.keys(item.options).length > 0) {
            tdOptions.textContent = Object.entries(item.options).map(([key, value]) => `${key}: ${value}`).join(", ");
        } else {
            tdOptions.textContent = "N/A";
        }
        row.appendChild(tdOptions);

        // Prix unitaire
        let tdPrice = document.createElement("td");
        tdPrice.textContent = `${parseFloat(item.price).toFixed(2)} €`;
        row.appendChild(tdPrice);

        // Quantité
        let tdQuantity = document.createElement("td");
        let quantityInput = document.createElement("input");
        quantityInput.type = "number";
        quantityInput.value = item.quantity;
        quantityInput.min = 1;
        quantityInput.addEventListener("change", function() {
            updateCartItem(index, parseInt(this.value));
        });
        tdQuantity.appendChild(quantityInput);
        row.appendChild(tdQuantity);

        // Sous-total
        let subtotal = item.price * item.quantity;
        total += subtotal;
        let tdSubtotal = document.createElement("td");
        tdSubtotal.textContent = `${subtotal.toFixed(2)} €`;
        row.appendChild(tdSubtotal);

        // Actions
        let tdActions = document.createElement("td");
        let deleteButton = document.createElement("button");
        deleteButton.textContent = "Supprimer";
        deleteButton.addEventListener("click", function() {
            removeCartItem(index);
        });
        tdActions.appendChild(deleteButton);
        row.appendChild(tdActions);

        tbody.appendChild(row);
    });

    table.appendChild(tbody);
    container.appendChild(table);

    // Afficher le total
    let totalDiv = document.createElement("div");
    totalDiv.className = "total";
    totalDiv.textContent = `Total : ${total.toFixed(2)} €`;
    container.appendChild(totalDiv);

    // Bouton "Valider le panier"
    let checkoutButton = document.createElement("button");
    checkoutButton.textContent = "Valider le panier";
    checkoutButton.addEventListener("click", function() {
        checkout();
    });
    container.appendChild(checkoutButton);
}

// Fonction pour mettre à jour la quantité d'un article du panier
async function updateCartItem(index, quantity) {
    try {
        let formData = new FormData();
        formData.append('index', index);
        formData.append('quantity', quantity);

        let response = await fetch('requester.php?action=updateCartItem', {
            method: 'POST',
            body: formData
        });
        let result = await response.json();

        if (result.success) {
            let cart = await fetchCart();
            renderCart(cart);
        } else {
            alert(result.error);
        }
    } catch (error) {
        console.error('Erreur lors de la mise à jour du panier :', error);
    }
}

// Fonction pour supprimer un article du panier
async function removeCartItem(index) {
    try {
        let formData = new FormData();
        formData.append('index', index);

        let response = await fetch('requester.php?action=removeCartItem', {
            method: 'POST',
            body: formData
        });
        let result = await response.json();

        if (result.success) {
            let cart = await fetchCart();
            renderCart(cart);
        } else {
            alert(result.error);
        }
    } catch (error) {
        console.error('Erreur lors de la suppression du panier :', error);
    }
}

// Fonction pour vérifier si l'utilisateur est connecté
async function isAuthenticated() {
    try {
        let response = await fetch('check_auth.php');
        let result = await response.json();
        return result.isAuthenticated;
    } catch (error) {
        console.error('Erreur lors de la vérification de l\'authentification :', error);
        return false;
    }
}

// Fonction pour valider le panier
async function checkout() {
    try {
        let response = await fetch('requester.php?action=checkout');
        let result = await response.json();

        if (result.success) {
            alert(result.success);
            window.location.href = 'index.html';
        } else if (result.error === 'Utilisateur non connecté') {
            window.location.href = 'login.html';
        } else {
            alert(result.error);
        }
    } catch (error) {
        console.error('Erreur lors de la validation du panier :', error);
    }
}

// Fonction pour gérer l'inscription
async function registerUser(username, password) {
    try {
        let formData = new FormData();
        formData.append('username', username);
        formData.append('password', password);

        let response = await fetch('requester.php?action=register', {
            method: 'POST',
            body: formData
        });
        let result = await response.json();

        if (result.success) {
            alert(result.success);
            window.location.href = 'login.html';
        } else {
            alert(result.error);
        }
    } catch (error) {
        console.error('Erreur lors de l\'inscription :', error);
    }
}

// Fonction pour gérer la connexion
async function loginUser(username, password) {
    try {
        let formData = new FormData();
        formData.append('username', username);
        formData.append('password', password);

        let response = await fetch('requester.php?action=login', {
            method: 'POST',
            body: formData
        });
        let result = await response.json();

        if (result.success) {
            alert(result.success);
            window.location.href = 'cart.html';
        } else {
            alert(result.error);
        }
    } catch (error) {
        console.error('Erreur lors de la connexion :', error);
    }
}

// Gestion du formulaire d'inscription
if (document.querySelector("#registerForm")) {
    document.querySelector("#registerForm").addEventListener("submit", function(event) {
        event.preventDefault();
        let username = document.querySelector("#username").value;
        let password = document.querySelector("#password").value;
        registerUser(username, password);
    });
}

// Gestion du formulaire de connexion
if (document.querySelector("#loginForm")) {
    document.querySelector("#loginForm").addEventListener("submit", function(event) {
        event.preventDefault();
        let username = document.querySelector("#username").value;
        let password = document.querySelector("#password").value;
        loginUser(username, password);
    });
}

// Fonction pour afficher le détail du produit
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

    if (document.querySelector("#cartContainer")) {
        let cart = await fetchCart();
        renderCart(cart);
    }
});
