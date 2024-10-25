<?php
/**
 * Classe ProductOption
 * Représente une option de produit avec les propriétés id, product_id, name, default_value.
 */
class ProductOption implements JsonSerializable {
    private int $id; // ID de l'option
    private int $product_id; // ID du produit associé
    private string $name; // Nom de l'option (ex: Taille, Couleur)
    private ?string $default_value; // Valeur par défaut

    public function __construct(int $id){
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function jsonSerialize(): mixed {
        return [
            "id" => $this->id,
            "product_id" => $this->product_id,
            "name" => $this->name,
            "default_value" => $this->default_value
        ];
    }

    public function getProductId(): int {
        return $this->product_id;
    }

    public function setProductId(int $product_id): self {
        $this->product_id = $product_id;
        return $this;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    public function getDefaultValue(): ?string {
        return $this->default_value;
    }

    public function setDefaultValue(?string $default_value): self {
        $this->default_value = $default_value;
        return $this;
    }

    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }
}
?>
