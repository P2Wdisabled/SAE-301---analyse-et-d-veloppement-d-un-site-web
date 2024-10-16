-- Création de la base de données
CREATE DATABASE IF NOT EXISTS potevin1;
USE potevin1;

-- Table des produits
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    pieces INT NOT NULL,
    age VARCHAR(10) NOT NULL
);

-- Table des catégories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Table de liaison produits-catégories
CREATE TABLE IF NOT EXISTS product_categories (
    product_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (product_id, category_id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Insertion des produits
INSERT INTO products (name, image, price, pieces, age) VALUES
('Super Mario World™ Mario et Yoshi', 'assets/SMWMY.jpg', 19.99, 1215, '18+'),
('Le vaisseau de transport impérial contre le speeder des éclaireurs rebelles', 'assets/VTICSER.jpg', 29.99, 383, '8+'),
('La couronne', 'assets/LC.jpg', 9.99, 1194, '18+');

-- Insertion des catégories
INSERT INTO categories (name) VALUES
('LEGO® Super Mario™'),
('Fantastique'),
('Jeux vidéo'),
('Star Wars™'),
('LEGO® Icons'),
('Collection Botanique');

-- Association des produits aux catégories
-- Produit 1
INSERT INTO product_categories (product_id, category_id) VALUES
(1, 1), -- LEGO® Super Mario™
(1, 2), -- Fantastique
(1, 3); -- Jeux vidéo

-- Produit 2
INSERT INTO product_categories (product_id, category_id) VALUES
(2, 4), -- Star Wars™
(2, 2); -- Fantastique

-- Produit 3
INSERT INTO product_categories (product_id, category_id) VALUES
(3, 5), -- LEGO® Icons
(3, 6); -- Collection Botanique
