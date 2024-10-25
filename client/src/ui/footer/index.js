import { genericRenderer } from "../../lib/utils.js"; 

const templateFile = await fetch("src/ui/footer/template.html.inc");
const template = await templateFile.text();


let FooterView = {

    render: function(){
        return template;
    }

}

export {FooterView};