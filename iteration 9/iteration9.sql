-- Création de la base de données
CREATE DATABASE IF NOT EXISTS potevin1;
USE potevin1;

-- Table des produits
DROP TABLE IF EXISTS products;
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    pieces INT NOT NULL,
    age VARCHAR(10) NOT NULL,
    stock INT NOT NULL DEFAULT 0
);

-- Table des catégories
DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Table de liaison produits-catégories
DROP TABLE IF EXISTS product_categories;
CREATE TABLE product_categories (
    product_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (product_id, category_id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Table des options
DROP TABLE IF EXISTS options;
CREATE TABLE options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Table de liaison produits-options
DROP TABLE IF EXISTS product_options;
CREATE TABLE product_options (
    product_id INT NOT NULL,
    option_id INT NOT NULL,
    option_value VARCHAR(255) NOT NULL,
    PRIMARY KEY (product_id, option_id, option_value),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (option_id) REFERENCES options(id)
);

-- Table des utilisateurs
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'user'
);

-- Table des commandes
DROP TABLE IF EXISTS orders;
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    date DATETIME NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'en cours',
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Table des articles de commande
DROP TABLE IF EXISTS order_items;
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    options JSON,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insertion des produits avec stock initial
INSERT INTO products (name, image, price, pieces, age, stock) VALUES
('Super Mario World™ Mario et Yoshi', 'assets/SMWMY.jpg', 19.99, 1215, '18+', 10),
('Le vaisseau de transport impérial', 'assets/VTICSER.jpg', 29.99, 383, '8+', 3),
('La couronne', 'assets/LC.jpg', 9.99, 1194, '18+', 0);

-- Insertion des catégories
INSERT INTO categories (name) VALUES
('LEGO® Super Mario™'),
('Fantastique'),
('Jeux vidéo'),
('Star Wars™'),
('LEGO® Icons'),
('Collection Botanique');

-- Association des produits aux catégories
INSERT INTO product_categories (product_id, category_id) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 4),
(2, 2),
(3, 5),
(3, 6);

-- Insertion des options
INSERT INTO options (name) VALUES
('Couleur'),
('Taille'),
('Version');

-- Association des options aux produits
INSERT INTO product_options (product_id, option_id, option_value) VALUES
(1, 1, 'Rouge'),
(1, 1, 'Vert'),
(1, 1, 'Bleu'),
(1, 2, 'Petit'),
(1, 2, 'Moyen'),
(1, 2, 'Grand'),
(2, 3, 'Standard'),
(2, 3, 'Édition Collector'),
(3, 1, 'Doré'),
(3, 1, 'Argenté'),
(3, 2, 'Unique');
