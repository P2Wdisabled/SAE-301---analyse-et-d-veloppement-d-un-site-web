<?php
/**
 * Classe OrderItem
 * Représente un item dans une commande.
 */
class OrderItem {
    private int $id;
    private int $order_id;
    private int $product_variant_id;
    private int $quantity;
    private float $unit_price;
    private float $total_price;
    private string $created_at;
    private string $updated_at;

    public function __construct(int $id){
        $this->id = $id;
    }

    // Getters et Setters
    public function getId(): int {
        return $this->id;
    }

    public function getOrderId(): int {
        return $this->order_id;
    }

    public function setOrderId(int $order_id): self {
        $this->order_id = $order_id;
        return $this;
    }

    public function getProductVariantId(): int {
        return $this->product_variant_id;
    }

    public function setProductVariantId(int $product_variant_id): self {
        $this->product_variant_id = $product_variant_id;
        return $this;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self {
        $this->quantity = $quantity;
        return $this;
    }

    public function getUnitPrice(): float {
        return $this->unit_price;
    }

    public function setUnitPrice(float $unit_price): self {
        $this->unit_price = $unit_price;
        return $this;
    }

    public function getTotalPrice(): float {
        return $this->total_price;
    }

    public function setTotalPrice(float $total_price): self {
        $this->total_price = $total_price;
        return $this;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at): self {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): string {
        return $this->updated_at;
    }

    public function setUpdatedAt(string $updated_at): self {
        $this->updated_at = $updated_at;
        return $this;
    }

    // Ajoutez d'autres propriétés et méthodes si nécessaire
}
?>
