<?php
/**
 * Classe Cart
 * Représente un panier avec les propriétés id, user_id, items, total.
 */
class Cart implements JsonSerializable {
    private int $id; // ID du panier
    private int $user_id; // ID de l'utilisateur propriétaire
    private array $items = []; // Tableau des items dans le panier
    private float $total = 0.0; // Montant total du panier

    public function __construct(int $id){
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function jsonSerialize(): mixed {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "items" => $this->items,
            "total" => $this->total
        ];
    }

    public function getUserId(): int {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self {
        $this->user_id = $user_id;
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

    public function addItem(array $item): self {
        // Vérifier si le produit existe déjà dans le panier
        foreach ($this->items as &$existingItem) {
            if ($existingItem['product_id'] === $item['product_id']) {
                $existingItem['quantity'] += $item['quantity'];
                $this->calculateTotal();
                return $this;
            }
        }
        // Si le produit n'existe pas, l'ajouter
        $this->items[] = $item;
        $this->calculateTotal();
        return $this;
    }

    public function updateItem(int $product_id, int $quantity): self {
        foreach ($this->items as &$item) {
            if ($item['product_id'] === $product_id) {
                if ($quantity <= 0) {
                    // Supprimer l'item si la quantité est nulle ou négative
                    $this->removeItem($product_id);
                    return $this;
                }
                $item['quantity'] = $quantity;
                break;
            }
        }
        $this->calculateTotal();
        return $this;
    }

    public function removeItem(int $product_id): self {
        foreach ($this->items as $index => $item) {
            if ($item['product_id'] === $product_id) {
                unset($this->items[$index]);
                $this->items = array_values($this->items); // Réindexer le tableau
                break;
            }
        }
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
