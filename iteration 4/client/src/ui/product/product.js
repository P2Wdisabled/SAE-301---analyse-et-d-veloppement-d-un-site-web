import { genericRenderer } from "../../lib/utils.js"; 
import { ProductData } from "../../data/product.js";


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
        let quantity = document.getElementById("quantity");
        if (quantity != null || quantity != undefined) {
            let quantity = document.getElementById("quantity");
            quantity.maxValue = 5;
            //max-value="5"
            let less = document.getElementById("less");
            let more = document.getElementById("more");
            more.addEventListener("click", function () {
                if (parseInt(quantity.value) + 1 > quantity.maxValue){return;}
                quantity.value = parseInt(quantity.value) + 1;
            });
            less.addEventListener("click", function () {
                
                if (parseInt(quantity.value) - 1 <= 0){return;}
                    quantity.value = parseInt(quantity.value) - 1;
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
        document.getElementById("add-cart").addEventListener("click", function () {
            let cart = JSON.parse(localStorage.getItem("cart"));
            if (cart == null) {
                cart = [];
            }
            let product = {
                id: document.getElementById("add-cart").dataset.id,
                quantity: document.getElementById("quantity").value
            };
            cart.push(product);
            localStorage.setItem("cart", JSON.stringify(cart));
            alert("Product added to cart");
            console.log(cart);
        });
    }
}

export {ProductLoad};
