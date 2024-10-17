-- Utiliser la base de données existante
USE potevin1;

-- Ajouter une table pour les options des produits
CREATE TABLE IF NOT EXISTS options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Table de liaison produits-options
CREATE TABLE IF NOT EXISTS product_options (
    product_id INT NOT NULL,
    option_id INT NOT NULL,
    option_value VARCHAR(255) NOT NULL,
    PRIMARY KEY (product_id, option_id, option_value),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (option_id) REFERENCES options(id)
);

-- Insérer des options
INSERT INTO options (name) VALUES
('Couleur'),
('Taille'),
('Version');

-- Associer des options aux produits
-- Produit 1
INSERT INTO product_options (product_id, option_id, option_value) VALUES
(1, 1, 'Rouge'),
(1, 1, 'Vert'),
(1, 1, 'Bleu'),
(1, 2, 'Petit'),
(1, 2, 'Moyen'),
(1, 2, 'Grand');

-- Produit 2
INSERT INTO product_options (product_id, option_id, option_value) VALUES
(2, 3, 'Standard'),
(2, 3, 'Édition Collector');

-- Produit 3
INSERT INTO product_options (product_id, option_id, option_value) VALUES
(3, 1, 'Doré'),
(3, 1, 'Argenté'),