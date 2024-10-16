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

// Fonction pour afficher les produits (identique à l'itération 1)
function renderProducts(products) {
    let container = document.querySelector("#productsContainer");
    container.innerHTML = '';

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

        div.appendChild(img);
        div.appendChild(h2);
        div.appendChild(p1);
        div.appendChild(p2);

        container.appendChild(div);
    });
}

// Chargement initial
document.addEventListener('DOMContentLoaded', async function() {
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
});
