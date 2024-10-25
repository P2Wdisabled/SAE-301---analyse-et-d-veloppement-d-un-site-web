let C = {}
C.init = function() {
    let product = [
        { id: 1, name: "Produit A", stock_quantity: 10, unit_price: 19.99 },
        { id: 2, name: "Produit B", stock_quantity: 10, unit_price: 59.99 }, // Épuisé
        { id: 3, name: "Produit C", stock_quantity: 4, unit_price: 9.99 },  // Bientôt épuisé
    ];

  
    }
        function addToCart(product, quantity) {
            if (product.stock_quantity === 0) {
                alert("Ce produit est temporairement indisponible.");
                return;
            }
            if (quantity > product.stock_quantity) {
                alert("La quantité demandée dépasse le stock disponible.");
                quantity = product.stock_quantity; // Limite à ce qui est disponible
            }
            else {
                alert("Produit ajouté au panier.");
            }
}
function updateCartItemQuantity(cartItem, newQuantity) {
    if (!cartItem) {
        console.log("Produit non trouvé dans le panier.");
        return;
    } else {
    cartItem.quantity = newQuantity;
    console.log(`Quantité mise à jour : ${cartItem.name}, nouvelle quantité : ${cartItem.quantity}`);
}

addToCart(product[0], 5);






    }

C.init();