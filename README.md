# Introduction à Symfony 6.4

Cette application permet de gérer un catalogue d’articles par catégories,
d’effectuer des recherches multi-critères, d’ajouter des articles avec images,
et de générer des **bons de commande professionnels au format Excel (.xlsx)**.

Développée dans un but **pédagogique**, elle illustre l’utilisation des composants
modernes de Symfony, de bundles populaires comme **VichUploaderBundle** et
**LiipImagineBundle**, ainsi qu'une **intégration de PHPSpreadsheet** pour
la génération de documents.

---

## Fonctionnalités principales

### Front office – Navigation et recherche

- Page d’accueil avec barre de navigation simple
- Liste des articles avec pagination
- **Filtrage** par :
    - **Catégorie**
    - **Fourchette de prix min / max**
- Affichage des détails d’un article (image, description, prix, etc.)

### Gestion des articles (admin)

- Ajout, modification et suppression d’articles
- Upload d’image via **VichUploaderBundle**
- Redimensionnement automatique avec **LiipImagineBundle**
- Association des articles à une **catégorie**

### Catégories

- Gestion CRUD des catégories d’articles

### Génération d'une liste d'achat au format PDF

- Génération automatique à partir de la liste d'achat :
    - Prénom et nom du client
    - date
    - Tableau d’articles commandés

---

### Génération de bons de commande au format Excel

- Génération automatique de bons de commande avec :
    - Coordonnées fournisseur et client
    - Tableau d’articles commandés
    - Totaux HT, TVA, TTC
    - Mise en forme avec PHPSpreadsheet

---

## Caractéristiques techniques

- **Symfony 6.4**
- **PHP 8.3+**
- **Doctrine ORM**
- **Twig**
- **PHPSpreadsheet**
- **VichUploaderBundle**
- **LiipImagineBundle**
- **Bootstrap 5 (via Twig ou Webpack Encore)**
- **MySQL / MariaDB**

---

## Struture des fichiers


```shell
├── src
│   ├── Controller
│   │   ├── ArticleController.php
│   │   ├── CartController.php
│   │   ├── CategoryController.php
│   │   ├── HomeController.php
│   │   ├── SearchArticleController.php
│   │   ├── SecurityController.php
│   │   └── UserController.php
│   ├── Entity
│   │   ├── Article.php
│   │   ├── Category.php
│   │   └── User.php
│   ├── Form
│   │   ├── ArticleType.php
│   │   ├── CategoryPriceSearchType.php
│   │   ├── CategoryType.php
│   │   └── UserType.php
│   ├── Kernel.php
│   ├── Listener
│   │   └── ImageUploadListener.php
│   ├── Model
│   │   └── CategoryPriceSearch.php
│   ├── Repository
│   │   ├── ArticleRepository.php
│   │   ├── CategoryRepository.php
│   │   └── UserRepository.php
│   └── Validator
│       └── PasswordValidator.php
├── templates
│   ├── article
│   │   ├── _delete_form.html.twig
│   │   ├── edit.html.twig
│   │   ├── _form.html.twig
│   │   ├── index.html.twig
│   │   ├── new.html.twig
│   │   ├── search_article.html.twig
│   │   ├── _search_form.html.twig
│   │   └── show.html.twig
│   ├── base.html.twig
│   ├── bundles
│   │   └── TwigBundle
│   ├── cart
│   │   ├── index.html.twig
│   │   └── pdf.html.twig
│   ├── category
│   │   ├── _delete_form.html.twig
│   │   ├── edit.html.twig
│   │   ├── _form.html.twig
│   │   ├── index.html.twig
│   │   ├── new.html.twig
│   │   └── show.html.twig
│   ├── home
│   │   └── home.html.twig
│   ├── partials
│   │   ├── footer.html.twig
│   │   └── header.html.twig
│   ├── security
│   │   ├── login.html.twig
│   │   └── registration.html.twig
│   └── user
│       ├── _delete_form.html.twig
│       ├── edit.html.twig
│       ├── _form.html.twig
│       ├── index.html.twig
│       ├── new.html.twig
│       └── show.html.twig

```

