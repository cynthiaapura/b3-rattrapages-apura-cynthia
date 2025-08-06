# Picard App – Interface de gestion des distributeurs (Front Vue.js)

## Contexte

Avec environ 1000 points de vente, l’entreprise Picard s’est imposée sur le marché de 
la distribution de produits alimentaires surgelés. 
Afin de toucher un nouveau public, Picard souhaite rendre son offre plus facilement 
accessible auprès des étudiants grâce à la mise en place de distributeurs réfrigérés
permettant de vendre ses produits.

Ce projet front-end, développé dans le cadre du module « Framework Front », répond à ce besoin avec une interface utilisateur intuitive permettant de visualiser, filtrer, noter et gérer des produits.

## Fonctionnalités

- Affichage d’une liste complète de produits
- Détail des produits dans une modale (quantité, description, dates, prix…)
- Notation des produits (système d’étoiles)
- Ajout de nouveaux produits via un formulaire modal
- Suppression de produits
- Modification de la quantité
- Filtrage par catégorie
- Indicateur (pastille) de disponibilité automatique selon la quantité
- Persistance de l’état via `localStorage`

## Architecture technique

### Technologies utilisées

- Vue.js 2 (Vue CLI)
- JavaScript ES6
- HTML5 / CSS3
- LocalStorage API
- Fichier JSON local (`products.json`)

### Structure du projet

```
picard_app/
├── public/
│ └── products.json # Source locale simulant l'API
├── src/
│ ├── components/
│ │ ├── AddProduct.vue
│ │ ├── AddProductModal.vue
│ │ ├── CategoryFilter.vue
│ │ ├── ProductList.vue
│ │ └── ProductModal.vue
│ ├── assets/
│ │ └── logo.png
│ │ └── style.css
│ ├── App.vue
│ └── main.js
```
### Composants clés

- `App.vue` : point d'entrée, gère l’état global (produits, filtres, modales)
- `ProductList.vue` : liste des produits avec suppression, notation
- `ProductModal.vue` : vue détaillée + modification de la quantité
- `CategoryFilter.vue` : sélection de la catégorie à afficher
- `AddProductModal.vue` : conteneur de la modale d’ajout
- `AddProduct.vue` : formulaire d’ajout de produit avec validation

## Stockage et données

- Les données sont chargées depuis un fichier `products.json` au premier chargement.
- Elles sont ensuite persistées dans le navigateur via `localStorage`.
- Les interactions utilisateur (ajout, modification, suppression, notation) sont immédiatement synchronisées.

## Déploiement en ligne

Une version en ligne est accessible ici :
https://picard-application.onrender.com

## Installation et exécution

### Prérequis

- Node.js >= 14.x
- Vue CLI (`npm install -g @vue/cli`)

### Étapes

```bash
git clone https://github.com/cynthiaapura/b3-rattrapages-apura-cynthia.git
cd b3-rattrapages-apura-cynthia/14-frameworks-front/picard_app
npm install
npm run serve
```
L’application est ensuite disponible sur http://localhost:8080

## Vidéo explicative