// src/data/cart.js

import { getRequest, postRequest, putRequest, deleteRequest } from '../lib/api-request.js';

let CartData = {
    cartToken: null,

    init: function() {
        // Charger le token depuis le localStorage
        this.cartToken = localStorage.getItem('cartToken');
    },

    saveToken: function(token) {
        this.cartToken = token;
        localStorage.setItem('cartToken', token);
    },

    fetch: async function() {
        let uri = 'cart';
        if (this.cartToken) {
            uri += '?cart_token=' + this.cartToken;
        }
        let data = await getRequest(uri);
        if (data && data.cart_token) {
            this.saveToken(data.cart_token);
        }
        return data;
    },

    addItem: async function(productVariantId, quantity = 1) {
        let uri = 'cart';
        if (this.cartToken) {
            uri += '?cart_token=' + this.cartToken;
        }
        let body = {
            product_variant_id: productVariantId,
            quantity: quantity
        };
        let result = await postRequest(uri, JSON.stringify(body));
        if (result && result.cart_token) {
            this.saveToken(result.cart_token);
        }
        return result;
    },

    updateItem: async function(productVariantId, quantity) {
        let uri = 'cart';
        if (this.cartToken) {
            uri += '?cart_token=' + this.cartToken;
        }
        let body = {
            product_variant_id: productVariantId,
            quantity: quantity
        };
        let result = await putRequest(uri, JSON.stringify(body));
        return result;
    },

    removeItem: async function(productVariantId) {
        let uri = 'cart';
        if (this.cartToken) {
            uri += '?cart_token=' + this.cartToken;
        }
        let body = {
            product_variant_id: productVariantId
        };
        let result = await deleteRequest(uri, JSON.stringify(body));
        return result;
    }
};

CartData.init();

export { CartData };
    