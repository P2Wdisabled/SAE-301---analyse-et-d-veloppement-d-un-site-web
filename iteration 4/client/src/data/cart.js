import {getRequest} from '../lib/api-request.js';


let CartData = {};
CartData.fetch = async function(id){
    let data = await getRequest('cart?token='+id);
    return [data];
}

CartData.save = async function(id, data){
    let options = {
        method: 'POST',
        body: JSON.stringify(data)
    }
    let result = await fetch('cart?token='+id, options);
    return result;
}


export {ProductData};