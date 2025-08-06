# API Picard Distributeurs Réfrigérés

## Contexte

Avec environ 1000 points de vente, l’entreprise Picard s’est imposée sur le marché de la distribution de produits alimentaires surgelés. 
Afin de toucher un nouveau public, Picard souhaite rendre son offre plus facilement accessible auprès des étudiants grâce à la mise en place de distributeurs réfrigérés permettant de vendre ses produits.

Ce projet d’API a pour but de permettre à ces distributeurs de proposer l’offre Picard en s’appuyant sur Symfony et API Platform.

## Description fonctionnelle

- Affichage d’une liste de produits
- Accès au détail d’un produit
- Notation des produits
- Ajout ou suppression d’un produit dans un panier
- Validation d’un panier, sans système de paiement

## Architecture technique

### Technologies principales

- **Symfony 7.3**
- **API Platform 4.1**
- **Doctrine ORM 3.5**
- **PostgreSQL**
- **Twig** (interface de démo)
- **FakerPHP** (génération de données de test)

### Architecture logicielle

L’API repose sur trois entités principales :

- `Product` : représente un produit Picard
- `Cart` : panier contenant des produits
- `CartItem` : ligne de panier (produit + quantité)

Ces entités sont exposées automatiquement via API Platform, générant des endpoints REST.

### Flux d’exécution

L’application a été développée sans Docker, avec un environnement local PHP 8.2+. La base PostgreSQL est initialisée par Doctrine et peuplée avec des fixtures.

Une interface web de test est accessible via Twig.

## Installation

### Prérequis

- PHP >= 8.2
- Composer
- PostgreSQL
- Symfony CLI (optionnel mais recommandé)

### Étapes

1. **Cloner le dépôt**
```bash
git clone https://github.com/cynthiaapura/b3-rattrapages-apura-cynthia.git
cd b3-rattrapages-apura-cynthia/11-architecture-des-donnees-creation-d-apis/picard-api
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configurer la base de données**

Le projet utilise PostgreSQL comme système de gestion de base de données. Il est nécessaire de configurer correctement la connexion pour que les commandes Symfony fonctionnent.

**-> Modifier la variable `DATABASE_URL`**

Dans le fichier `.env` **ou de préférence** dans un fichier `.env.local` (qui surcharge `.env` et n’est pas versionné par Git), modifiez la ligne suivante :

```env
DATABASE_URL="postgresql://yourUser:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
```
Remplacez les valeurs par:
- `yourUser`: le nom de l'utilisateur PostgreSQL existant sur votre machine
- `!ChangeMe!`: le mot de passe de cet utilisateur
- `app`: le nom souhaité pour la base de données (par exemple picard-api)

**-> Créer l'utilisateur PostgreSQL (si nécessaire)**

Si vous n’avez pas encore d’utilisateur PostgreSQL, vous pouvez en créer un via les commandes suivantes :

```bash
# Se connecter à PostgreSQL en tant qu’administrateur
sudo -u postgres psql

# Créer un nouvel utilisateur avec un mot de passe
CREATE USER picard_user WITH PASSWORD 'monMotDePasse';

# Donner les droits de création de base
ALTER USER picard_user CREATEDB;

# Quitter le shell PostgreSQL
\q
```

**-> Démarrer PostgreSQL (si ce n’est pas déjà fait)**
```bash
sudo service postgresql start
```

4. **Créer la base et exécuter les migrations**
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. **Charger les fixtures de test**
```bash
php bin/console doctrine:fixtures:load
```
On vous demandera si vous souhaitez purger la base que vous avez créer pour le projet, répondez yes.

6. **Démarrer le serveur**
```bash
symfony server:start
# ou
php -S localhost:8000 -t public
```
Attention pour la commande php, le flag est un `s` majuscule. Ce flag permet de lancer le serveur PHP intégré

Interface disponible sur : `http://localhost:8000/`

## Utilisation de l’API

### Endpoints REST générés par API Platform

| Ressource    | Méthodes                    | URL de base             | Description                                 |
|--------------|-------------------------------|--------------------------|---------------------------------------------|
| Product      | `GET`, `POST`, `PATCH`, `DELETE` | `/api/products`        | CRUD sur les produits                       |
|              | `GET`                         | `/api/products/{id}`     | Détail d'un produit                        |
| Cart         | `GET`, `POST`, `PATCH`, `DELETE` | `/api/carts`           | CRUD sur les paniers                        |
|              | `GET`                         | `/api/carts/{id}`        | Détail d’un panier                         |
| CartItem     | `GET`, `POST`, `PATCH`, `DELETE` | `/api/cart_items`      | CRUD sur les lignes de panier               |
|              | `GET`                         | `/api/cart_items/{id}`   | Détail d’une ligne de panier              |

### Routes Symfony personnalisées (interface utilisateur Twig)

| Nom               | Méthode | URL                        | Description                           |
|-------------------|----------|----------------------------|---------------------------------------|
| `app_cart`        | `GET`    | `/cart`                    | Affichage du panier                   |
| `app_cart_add`    | `POST`   | `/cart/add/{id}`           | Ajout d’un produit au panier          |
| `app_cart_remove` | `POST`   | `/cart/remove/{id}`        | Suppression d’un produit du panier    |
| `app_cart_validate`| `POST`  | `/cart/validate`           | Validation du panier                  |
| `app_product`     | `GET`    | `/`                         | Liste des produits                    |
| `app_product_detail`| `GET`  | `/products/{id}`           | Détail d’un produit                 |
| `app_product_rate`| `POST`   | `/products/{id}/rate`      | Notation d’un produit                 |

### Documentation API interactive

- Swagger UI : [http://localhost:8000/api/docs](http://localhost:8000/api/docs)

## Base de données & Entités

La base est construite avec Doctrine et synchronisée via migration.

### Synchronisation
```bash
php bin/console doctrine:migrations:migrate
```

### Génération des données de test
```bash
php bin/console doctrine:fixtures:load
```
## Tests et authentification

- Tests fonctionnels réalisés via Postman

> Aucun test unitaire n’est implémenté à ce stade. Cela peut être envisagé comme évolution.

Cette API est publique et sans système d’authentification.
Pour une mise en production, une intégration de JWT ou de sessions serait recommandée.

## Sources

[Docs API Platform](https://api-platform.com/docs/symfony)
[Getting Started](https://api-platform.com/docs/core/getting-started)
[Docs Symfony](https://symfony.com/doc/current/index.html)
J'ai naviguée dans la doc de Symfony entre autre pour la mise en place du projet, ainsi que l'utilisation de Doctrine ou encore les templatings avec Twig

## Vidéo explicative
