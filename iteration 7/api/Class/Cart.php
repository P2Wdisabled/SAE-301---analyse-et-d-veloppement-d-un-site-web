<?php
/**
 * Classe Cart
 * Représente un panier avec id, token, items, total.
 */
class Cart implements JsonSerializable {
    private int $id; // ID du panier
    private string $token; // Token unique du panier
    private array $items = []; // Items du panier
    private float $total = 0.0; // Montant total

    public function __construct(int $id){
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function jsonSerialize(): mixed {
        return [
            "id" => $this->id,
            "token" => $this->token,
            "items" => $this->items,
            "total" => $this->total
        ];
    }

    public function getToken(): string {
        return $this->token;
    }

    public function setToken(string $token): self {
        $this->token = $token;
        return $this;
    }

    public function getItems(): array {
        return $this->items;
    }

    public function setItems(array $items): self {
        $this->items = $items;
        $this->calculateTotal();
        return $this;
    }

    public function getTotal(): float {
        return $this->total;
    }

    private function calculateTotal(): void {
        $this->total = 0.0;
        foreach ($this->items as $item) {
            $this->total += $item['price'] * $item['quantity'];
        }
    }

    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }
}
?>
