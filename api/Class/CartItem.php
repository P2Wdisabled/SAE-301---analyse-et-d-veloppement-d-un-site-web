<?php
/**
 * Classe CartItem
 * Représente un item dans le panier avec les propriétés id, cart_id, product_id, quantity.
 */
class CartItem implements JsonSerializable {
    private int $id; // ID de l'item
    private int $cart_id; // ID du panier associé
    private int $product_id; // ID du produit
    private int $quantity; // Quantité du produit

    public function __construct(int $id){
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function jsonSerialize(): mixed {
        return [
            "id" => $this->id,
            "cart_id" => $this->cart_id,
            "product_id" => $this->product_id,
            "quantity" => $this->quantity
        ];
    }

    public function getCartId(): int {
        return $this->cart_id;
    }

    public function setCartId(int $cart_id): self {
        $this->cart_id = $cart_id;
        return $this;
    }

    public function getProductId(): int {
        return $this->product_id;
    }

    public function setProductId(int $product_id): self {
        $this->product_id = $product_id;
        return $this;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self {
        $this->quantity = $quantity;
        return $this;
    }

    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }
}
?>
