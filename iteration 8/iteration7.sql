-- Utiliser la base de données existante
USE potevin1;

-- Modifier la table users pour ajouter le champ role
ALTER TABLE users ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'user';

-- Mettre à jour les utilisateurs existants (si nécessaire)
-- Par exemple, créer un administrateur
INSERT INTO users (username, password, role) VALUES
('potevin1', '$2y$10$w40BwnsW8Imq2ZbYCJIBXOOxwFnVZRSBCXzqb4Je3dlmRhoZoM78i', 'admin'); -- Remplacez 'admin_password_hash' par le hash du mot de passe de l'administrateur
