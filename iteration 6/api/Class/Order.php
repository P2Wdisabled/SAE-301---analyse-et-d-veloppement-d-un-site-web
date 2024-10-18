<?php
/**
 * Classe Order
 * Représente une commande.
 */
class Order {
    private int $id;
    private int $user_id;
    private string $status;
    private float $total_amount;
    private string $created_at;
    private string $updated_at;

    public function __construct(int $id){
        $this->id = $id;
    }

    // Getters et Setters
    public function getId(): int {
        return $this->id;
    }

    public function getUserId(): int {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self {
        $this->user_id = $user_id;
        return $this;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function setStatus(string $status): self {
        $this->status = $status;
        return $this;
    }

    public function getTotalAmount(): float {
        return $this->total_amount;
    }

    public function setTotalAmount(float $total_amount): self {
        $this->total_amount = $total_amount;
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
