<div class="cart-item flex items-center justify-between border-b py-4">
    <div class="flex items-center">
        <img src="{{image_url}}" alt="{{name}}" class="w-20 h-20 object-cover rounded mr-4">
        <div>
            <h3 class="font-bold text-lg">{{name}}</h3>
            <p class="text-gray-600">Prix unitaire : {{price}} €</p>
        </div>
    </div>
    <div class="flex items-center">
        <input type="number" min="1" value="{{quantity}}" data-product-variant-id="{{product_variant_id}}" class="item-quantity w-16 text-center border rounded mr-4">
        <p class="font-bold text-lg mr-4">{{subtotal}} €</p>
        <button class="remove-item text-red-500" data-product-variant-id="{{product_variant_id}}">Supprimer</button>
    </div>
</div>
