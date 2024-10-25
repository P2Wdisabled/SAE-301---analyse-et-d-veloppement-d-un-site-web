import { genericRenderer } from "../../lib/utils.js"; 
import { ProductData } from "../../data/product.js";
import { CartData } from "../../data/cart.js";


const templateFile = await fetch("src/ui/product/template-product.html.inc");
const template = await templateFile.text();

const templateFilend = await fetch("src/ui/product/template-product-tshirt.html.inc");
const templatend = await templateFilend.text();

let ProductLoad = {
    enable: function(){
        let products = document.querySelectorAll("button");
        products.forEach(product => {
            product.addEventListener('click', this.openProduct)
        });
    },
    render: function(data){
        let html = "";
        for(let obj of data){
            html += genericRenderer(template, obj);
        }
        return html;
    },
    rendernd: function(data){
        let html = "";
        for(let obj of data){
            html += genericRenderer(templatend, obj);
        }
        return html;
    },
    openProduct: async function(ev){
        if(ev.target.id != undefined) {
            let type = ev.target.dataset.type;
            if(type.includes("T-shirt")) {
                let productId = ev.target.id;
                let container = document.querySelector("#container");
                let data = await ProductData.fetch(productId);
                container.innerHTML = ProductLoad.rendernd(data);
                ProductLoad.details();
                return;
            }
            let productId = ev.target.id;
            let container = document.querySelector("#container");
            let data = await ProductData.fetch(productId);
            container.innerHTML = ProductLoad.render(data);
            ProductLoad.details();
        }
    },
    details: function(){
        document.getElementById("checkStockButton").addEventListener("click", function () {
            document.getElementById("stockPanel").classList.remove("hidden");
        });
        let quantityElement = document.getElementById("quantity");
if (quantityElement) {
    quantityElement.maxValue = 5;
    let less = document.getElementById("less");
    let more = document.getElementById("more");
    more.addEventListener("click", function () {
        let currentValue = parseInt(quantityElement.value) || 0;
        if (currentValue + 1 > quantityElement.maxValue) return;
        quantityElement.value = currentValue + 1;
    });
    less.addEventListener("click", function () {
        let currentValue = parseInt(quantityElement.value) || 0;
        if (currentValue - 1 <= 0) return;
        quantityElement.value = currentValue - 1;
    });
}


        document.getElementById("checkStockButton").addEventListener("click", function () {
            document.getElementById("stockPanel").classList.remove("hidden");
        });

        // Masquer le panneau de vérification de stock
        document.getElementById("closeButton1").addEventListener("click", function () {
            document.getElementById("stockPanel").classList.add("hidden");
        });
        // Afficher le contenu des livraisons et retours
        document.getElementById("livraisonButton").addEventListener("click", function () {
            document.getElementById("livraisonContent").classList.remove("hidden");
        });

        // Masquer le contenu des livraisons et retours
        document.getElementById("closeButton").addEventListener("click", function () {
            document.getElementById("livraisonContent").classList.add("hidden");
        });
//<span id="cart-qt" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">{{cart-qt}}</span>
    // Autres gestionnaires...

    // Gestion de l'ajout au panier
    let addCartButton = document.getElementById("add-cart");
    if (addCartButton) {
        addCartButton.addEventListener("click", async function () {
            let productVariantId = addCartButton.dataset.id;
            let quantityElement = document.getElementById("quantity");
            let quantity = parseInt(quantityElement.value);

            if (isNaN(quantity) || quantity <= 0) {
                alert("Veuillez entrer une quantité valide.");
                return;
            }

            let result = await CartData.addItem(productVariantId, quantity);
            if (result) {
                alert("Produit ajouté au panier !");
                // Mettre à jour l'affichage du panier si nécessaire
            } else {
                alert("Erreur lors de l'ajout au panier.");
            }
        });
    }
}
}

export {ProductLoad};
