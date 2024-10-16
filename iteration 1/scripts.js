// scripts.js

// Fonction pour récupérer tous les produits
async function fetchAllProducts() {
    try {
        let response = await fetch('requester.php?action=getAllProducts');
        let products = await response.json();
        return products;
    } catch (error) {
        console.error('Erreur lors de la récupération des produits :', error);
    }
}

// Fonction pour afficher les produits
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

// Chargement initial des produits
document.addEventListener('DOMContentLoaded', async function() {
    let products = await fetchAllProducts();
    renderProducts(products);
});
