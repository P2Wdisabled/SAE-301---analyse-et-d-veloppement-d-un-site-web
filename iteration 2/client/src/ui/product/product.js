import { genericRenderer } from "../../lib/utils.js"; 
import { ProductData } from "../../data/product.js";


const templateFile = await fetch("src/ui/product/template-product.html.inc");
const template = await templateFile.text();

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
    openProduct: async function(ev){
        if(ev.target.id != undefined) {
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

    }
}

export {ProductLoad};
