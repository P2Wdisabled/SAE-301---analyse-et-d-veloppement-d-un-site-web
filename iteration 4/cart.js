// Fonction pour afficher le panier
function renderCart() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
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
        tdOptions.textContent = Object.entries(item.options).map(([key, value]) => `${key}: ${value}`).join(", ");
        row.appendChild(tdOptions);

        // Prix unitaire
        let tdPrice = document.createElement("td");
        tdPrice.textContent = `${item.price.toFixed(2)} €`;
        row.appendChild(tdPrice);

        // Quantité
        let tdQuantity = document.createElement("td");
        let quantityInput = document.createElement("input");
        quantityInput.type = "number";
        quantityInput.value = item.quantity;
        quantityInput.min = 1;
        quantityInput.max = 5;
        quantityInput.addEventListener("change", function() {
            updateQuantity(index, parseInt(this.value));
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
            removeFromCart(index);
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
}

// Fonction pour mettre à jour la quantité d'un produit
function updateQuantity(index, newQuantity) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    if (cart[index]) {
        cart[index].quantity = newQuantity;
        localStorage.setItem("cart", JSON.stringify(cart));
        renderCart();
    }
}

// Fonction pour supprimer un produit du panier
function removeFromCart(index) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    cart.splice(index, 1);
    localStorage.setItem("cart", JSON.stringify(cart));
    renderCart();
}

// Afficher le panier au chargement de la page
renderCart();
