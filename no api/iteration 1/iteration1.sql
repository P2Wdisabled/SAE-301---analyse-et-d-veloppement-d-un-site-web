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

-- Insertion de données exemple
INSERT INTO products (name, image, price, pieces, age) VALUES
('Super Mario World™ Mario et Yoshi', 'assets/SMWMY.jpg', 19.99, 1215, '18+'),
('Le vaisseau de transport impérial contre le speeder des éclaireurs rebelles', 'assets/VTICSER.jpg', 29.99, 383, '8+'),
('La couronne', 'assets/LC.jpg', 9.99, 1194, '18+');
