// src/ui/cart/cart.js

import { genericRenderer } from "../../lib/utils.js";
import { CartData } from "../../data/cart.js";

const templateFile = await fetch("src/ui/cart/template.html.inc");
const template = await templateFile.text();

let CartView = {

    render: async function(){
        let data = await CartData.fetch();
        if (data && data.cart) {
            let html = "";
            for (let item of data.cart.items) {
                // Calcul du sous-total pour chaque article
                item.subtotal = (item.price * item.quantity).toFixed(2);
                html += genericRenderer(template, item);
            }
            document.getElementById("cart-container").innerHTML = html;
            // Afficher le total du panier
            this.renderTotal(data.cart);
            // Mettre à jour le compteur du panier
            this.updateCartCount(data.cart);
        } else {
            document.getElementById("cart-container").innerHTML = "<p>Votre panier est vide.</p>";
            document.getElementById("cart-total").textContent = "";
            this.updateCartCount({ items: [] });
        }
    },

    renderTotal: function(cart) {
        let totalElement = document.getElementById("cart-total");
        if (totalElement) {
            totalElement.textContent = `Total : ${cart.total.toFixed(2)} €`;
        }
    },

    updateCartCount: function(cart) {
        let totalItems = cart.items.reduce((sum, item) => sum + item.quantity, 0);
        let cartCountElement = document.getElementById("cart-count");
        if (cartCountElement) {
            cartCountElement.textContent = totalItems;
        }
    },

    init: function(){
        this.render();
        this.addEventListeners();
    },

    addEventListeners: function(){
        document.addEventListener('click', async (event) => {
            if (event.target.classList.contains('remove-item')) {
                let productVariantId = event.target.getAttribute('data-product-variant-id');
                let result = await CartData.removeItem(productVariantId);
                if (result) {
                    this.render();
                } else {
                    alert("Erreur lors de la suppression de l'article.");
                }
            }
        });

        document.addEventListener('change', async (event) => {
            if (event.target.classList.contains('item-quantity')) {
                let productVariantId = event.target.getAttribute('data-product-variant-id');
                let quantity = parseInt(event.target.value);
                if (isNaN(quantity) || quantity <= 0) {
                    quantity = 1;
                    event.target.value = 1;
                }
                let result = await CartData.updateItem(productVariantId, quantity);
                if (result) {
                    this.render();
                } else {
                    alert("Erreur lors de la mise à jour de la quantité.");
                }
            }
        });
    }
};

export { CartView };
