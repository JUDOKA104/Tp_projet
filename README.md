# 🛒 CUBIC MARKET - Projet PHP POO

![Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![DB](https://img.shields.io/badge/PostgreSQL-17-336791?style=for-the-badge&logo=postgresql&logoColor=white)
![Style](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)

> **Cubic Market** est une application web e-commerce développée en **PHP Orienté Objet** destinée à la vente d'items pour un serveur Minecraft fictif. Ce projet met en œuvre une architecture **MVC** (Modèle-Vue-Contrôleur) stricte sans framework, démontrant la maîtrise des concepts avancés du langage.

---

## 🏗️ Architecture & Arborescence

Le projet respecte les standards **PSR-4** avec un Autoloader personnalisé et une structure claire :

```text
TP_PROJET/
├── 📁 bdd/                # Export de la base de données (.sql)
├── 📁 config/             # Configuration de la BDD (db.php)
├── 📁 public/             # Point d'entrée unique (Web Root)
│   ├── css/               # Styles (Thème Minecraft Dark)
│   ├── img/               # Uploads et assets
│   ├── js/                # Scripts (Filtres, Modales)
│   └── index.php          # Front Controller & Routing
├── 📁 pdf/                # Fichier pdf explicatif pour l'installation
├── 📁 src/                # Cœur logique (Namespace App\)
│   ├── 📂 Controller/     # Orchestration (Shop, Admin, Auth...)
│   ├── 📂 Core/           # Noyau (Router, Database Singleton, Autoloader)
│   ├── 📂 Entity/         # Objets Métiers (User, Product, Weapon...)
│   ├── 📂 Model/          # Managers & Accès BDD (PDO)
│   └── 📂 Payment/        # Implémentation du Design Pattern Strategy
│
└── 📁 views/              # Templates HTML/PHP
```

---

## 💻 Fonctionnalités Techniques

### 1. Programmation Orientée Objet (POO)
* **Héritage :** Classe mère `Product` étendue par `Weapon` et `Rank`.
* **Encapsulation :** Propriétés typées privées/protégées avec Getters/Setters.
* **Interfaces & Polymorphisme :** Système de paiement flexible via `PaymentInterface`.

### 2. Design Patterns
* **MVC :** Séparation stricte entre la logique, les données et l'affichage.
* **Singleton :** Connexion unique à la base de données via `Database::getConnection()`.
* **Strategy :** Gestion dynamique des méthodes de paiement (Carte Bancaire vs PayPal) sans `if/else` complexes.

### 3. Sécurité Avancée 🔐
* **Mots de passe :** Hachage robuste utilisant **Argon2id** combiné à un **Poivrage (Pepper)** personnalisé en SHA-256.
* **Injections SQL :** 100% des requêtes utilisent `PDO::prepare()`.
* **Faille XSS :** Échappement automatique des sorties via un helper `e()`.
* **CSRF :** Vérification de tokens sur tous les formulaires POST.

---

## 🧪 Comptes de Démonstration

Pour tester les différents niveaux d'accès (RBAC) :

| Rôle | Email | Mot de Passe | Spécificité |
| :--- | :--- | :--- | :--- |
| **ADMIN** | `maxime.tournier@tyrolium.fr` | `RootADMIN14856@!` | Accès Back-Office, CRUD complet, God Mode. |
| **USER** | `test@gmail.com` | `TestUser123@` | Achat d'items, gestion de profil, inventaire. |

---

## 🌟 Points Clés du Projet
* [x] Système de **Panier & Inventaire** persistant en BDD (Relation Many-to-Many).
* [x] **Back-Office** complet pour la gestion des produits (Ajout, Modif, Suppression, Upload images).
* [x] Interface utilisateur réactive (Thème sombre "Gaming").
* [x] Simulation de paiement réaliste avec feedback visuel.

---

*Développé par Enzo ORIOL & Paul MERCIER-BOUVARD - IPSSI 2026*