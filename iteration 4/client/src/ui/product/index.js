import { genericRenderer } from "../../lib/utils.js"; 

const templateFile = await fetch("src/ui/product/template.html.inc");
const template = await templateFile.text();


let ProductView = {

    render: function(data){
        let html = "";
        for(let obj of data){
            html += genericRenderer(template, obj);
        }
        return html;
    }
    

}

export {ProductView};
/*

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
*/