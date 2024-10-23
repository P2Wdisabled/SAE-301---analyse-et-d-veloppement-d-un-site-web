<?php
/**
 * Classe Category
 * Représente une catégorie avec les propriétés id, name, description.
 */
class Category implements JsonSerializable {
    private int $id; // ID de la catégorie
    private string $name; // Nom de la catégorie
    private string $description; // Description de la catégorie
    private array $products;

    public function __construct(int $id){
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function jsonSerialize(): mixed {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "products" => $this->products,
        ];
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(string $description): self {
        $this->description = $description;
        return $this;
    }

    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }

    public function getProducts(): array {
        return $this->products;
    }

    public function setProducts(array $products): self {
        $this->products = $products;
        return $this;
    }
}
?>
