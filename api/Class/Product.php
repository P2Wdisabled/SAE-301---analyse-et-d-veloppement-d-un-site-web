<?php
/**
 * Classe Product
 * Représente un produit avec les propriétés id, name, description, price, image_url, categories, options.
 */
class Product implements JsonSerializable {
    private int $id; // ID du produit
    private string $name; // Nom du produit
    private string $description; // Description du produit
    private float $price; // Prix du produit
    private string $image_url; // URL de l'image du produit
    private array $categories = []; // Tableau des IDs des catégories
    private array $options = []; // Tableau des options associées

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
            "image_url" => $this->image_url,
            "categories" => $this->categories,
            "options" => $this->options
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

    /**
     * Retourne les IDs des catégories associées au produit.
     */
    public function getCategories(): array {
        return $this->categories;
    }

    /**
     * Définit les catégories associées au produit.
     *
     * @param array $categories Tableau d'IDs de catégories.
     * @return self
     */
    public function setCategories(array $categories): self {
        $this->categories = $categories;
        return $this;
    }

    /**
     * Ajoute une catégorie au produit.
     *
     * @param int $category_id ID de la catégorie à ajouter.
     * @return self
     */
    public function addCategory(int $category_id): self {
        if (!in_array($category_id, $this->categories)) {
            $this->categories[] = $category_id;
        }
        return $this;
    }

    /**
     * Retourne les options associées au produit.
     */
    public function getOptions(): array {
        return $this->options;
    }

    /**
     * Définit les options associées au produit.
     *
     * @param array $options Tableau des options.
     * @return self
     */
    public function setOptions(array $options): self {
        $this->options = $options;
        return $this;
    }

    /**
     * Ajoute une option au produit.
     *
     * @param array $option Détails de l'option.
     * @return self
     */
    public function addOption(array $option): self {
        $this->options[] = $option;
        return $this;
    }

    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }
}
?>
