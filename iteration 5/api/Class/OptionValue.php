<?php
/**
 * Classe OptionValue
 * Représente une valeur d'option avec les propriétés id, product_option_id, value.
 */
class OptionValue implements JsonSerializable {
    private int $id; // ID de la valeur d'option
    private int $product_option_id; // ID de l'option associée
    private string $value; // Valeur (ex: Rouge, Bleu)

    public function __construct(int $id){
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function jsonSerialize(): mixed {
        return [
            "id" => $this->id,
            "product_option_id" => $this->product_option_id,
            "value" => $this->value
        ];
    }

    public function getProductOptionId(): int {
        return $this->product_option_id;
    }

    public function setProductOptionId(int $product_option_id): self {
        $this->product_option_id = $product_option_id;
        return $this;
    }

    public function getValue(): string {
        return $this->value;
    }

    public function setValue(string $value): self {
        $this->value = $value;
        return $this;
    }

    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }
}
?>
