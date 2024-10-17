<?php
/**
 * Classe Product
 * Représente un produit avec les propriétés id, name, description, price, image_url.
 */
class Product implements JsonSerializable {
    private int $id; // ID du produit
    private string $name; // Nom du produit
    private string $description; // Description du produit
    private float $price; // Prix du produit
    private string $image_url; // URL de l'image du produit

    public function __construct(int $id){
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    /**
     * Définition de la manière dont un produit est converti en JSON.
     */
    public function jsonSerialize(): mixed {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "price" => $this->price,
            "image_url" => $this->image_url
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

    public function getPrice(): float {
        return $this->price;
    }

    public function setPrice(float $price): self {
        $this->price = $price;
        return $this;
    }

    public function getImageUrl(): string {
        return $this->image_url;
    }

    public function setImageUrl(string $image_url): self {
        $this->image_url = $image_url;
        return $this;
    }

    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }
}
?>
