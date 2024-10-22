import { ProductData } from "./data/product.js";
import { ProductView } from "./ui/product/index.js";
//import './index.css';
import { NavbarView } from "./ui/navbar/index.js";
import { NavbarLoad } from "./ui/navbar/navbar.js";
import { FooterView } from "./ui/footer/index.js";
import { FooterLoad } from "./ui/footer/footer.js";

let C = {}
C.init = async function(){
    let html = ""
    html += NavbarView.render();
    let data = await ProductData.fetchAll();
    html += "<div class='flex flex-wrap'>"
    html  += ProductView.render(data);
    html += "</div>"
    html += FooterView.render();
    document.querySelector("body").innerHTML = html;
    NavbarLoad.enable();
    FooterLoad.enable();
}


C.init();