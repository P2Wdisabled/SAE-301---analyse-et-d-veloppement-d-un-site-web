import { genericRenderer } from "../../lib/utils.js"; 

const templateFile = await fetch("src/ui/navbar/template.html.inc");
const template = await templateFile.text();


let NavbarView = {

    render: function(){
        return template;
    }

}

export {NavbarView};