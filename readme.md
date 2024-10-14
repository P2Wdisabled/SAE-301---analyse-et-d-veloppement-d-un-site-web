# Projet LEGO - Site Web Click & Collect

## Description du projet

Ce projet consiste à développer un site web pour **LEGO** avec une fonctionnalité de **Click & Collect** en full code. Le site permet aux utilisateurs de parcourir des produits, de les ajouter à un panier, puis de récupérer leurs achats en magasin.

### Objectifs principaux :
- Créer une expérience utilisateur fluide et intuitive avec du code HTML, CSS, JavaScript et PHP.
- Permettre aux utilisateurs de naviguer à travers trois catégories de produits :
  - **Briques LEGO**
  - **sets marvel LEGO**
  - **vetements LEGO**
  
- Gérer les stocks de produits en temps réel et offrir la possibilité de récupérer les articles via le système de Click & Collect.

## Fonctionnalités

### 1. Parcours Utilisateur
- **Navigation produit** : Les utilisateurs peuvent parcourir 3 catégories principales de produits avec des filtres pour faciliter la recherche.
- **Détail produit** : Chaque produit a des options personnalisables comme la couleur ou la taille, et une gestion des stocks en temps réel.
- **Panier d'achat** : Ajout de produits au panier, modification des quantités et suppression d'articles.
- **Click & Collect** : Option pour que les utilisateurs récupèrent leurs produits directement en magasin.

### 2. Administration
- **Gestion des stocks** : Interface back-office développée pour gérer les stocks en PHP et MySQL.
- **Gestion des commandes** : Suivi des commandes et des statuts ("en cours", "disponible", "retirée", "annulée") via une interface dédiée.

## Technologies utilisées

- **Front-end** : HTML, CSS, JavaScript
- **Back-end** : PHP, MySQL
- **Gestion de projet** : Git, GitHub

## Itérations du développement

### Semaine 1 : Analyse et Webdesign
- **Analyse** : Étude des besoins utilisateurs et identification des parcours utilisateur.
- **Webdesign** : Création de maquettes fonctionnelles avec des outils comme Figma.
- **Intégration HTML/CSS** : Création des composants et des pages statiques.

### Semaine 2 : Développement
- **Développement back-end** : Création des bases de données MySQL et des API en PHP pour gérer les produits, le panier et les commandes.
- **Gestion du stock et Click & Collect** : Développement des fonctionnalités de gestion des stocks et mise en place du système de retrait en magasin.

## Instructions d'installation

1. Configurer la base de données :
   - Créez une base de données MySQL et importez le fichier `database.sql`.
   - Configurer le fichier `config.php` avec vos identifiants MySQL.

2. Lancer le projet localement :
   - Utilisez un serveur local comme **MAMP**, **XAMPP**, ou **WAMP** pour exécuter le site.
   - Accédez au site via `http://localhost`.

3. Accéder au back-office :
   - URL : `http://localhost/admin`
   - Identifiant : `admin`
   - Mot de passe : `admin123`

## Liens utiles

- **Lien Figma** : [Prototype Figma](https://www.figma.com/design/gRk6GiuOsZqZwGjuxP1L3k/Potevin_Pain-SAE301?node-id=5-2&t=NvNVVhNeuAh6411d-1)
- **Lighthouse Audit** : [Rapport Lighthouse](https://lighthouse.report...)

## Auteurs

- **Louis POTEVIN** - Développeur Full-Stack
- **Vivien PAIN** - Développeur
