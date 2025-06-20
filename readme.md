# 🎯 Gestion Événements avec Symfony 7

**Mini application web fonctionnelle de gestion d'événements avec authentification, gestion d'inscriptions aux événements, et interface administrateur sécurisée.**

---

## 🚀 Contexte du Projet

Ce projet Symfony 7 est une plateforme simple de gestion d’événements. Il permet :

* À un visiteur de s'inscrire et se connecter.
* Aux utilisateurs de consulter et s'inscrire à des événements.
* Aux administrateurs de gérer (CRUD) les événements, catégories, utilisateurs, et de valider les inscriptions.

---

## 📌 Technologies utilisées

* Symfony 7
* PHP 8+
* Twig
* Doctrine ORM
* Symfony Security
* Bootstrap 5

---

## 🛠 Installation du Projet

### 📦 Installation initiale

```bash
composer create-project symfony/skeleton gestion_evenements
cd gestion_evenements
composer require symfony/webapp-pack symfony/maker-bundle --dev symfony/security-bundle symfony/orm-pack annotations
```

### ⚙️ Configuration de la base de données

Dans le fichier `.env`, configure la connexion :

```dotenv
DATABASE_URL="mysql://root:@127.0.0.1:3306/gestion_evenements?serverVersion=8&charset=utf8mb4"
```

Puis :

```bash
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

---

## 🔐 Authentification & Sécurité

### Création de l'utilisateur

```bash
php bin/console make:user
```

### Connexion

```bash
php bin/console make:auth
```

### Inscription (création de compte)

```bash
php bin/console make:registration-form
```

> ⚠️ Si le nom du contrôleur est en conflit avec une autre classe `RegistrationController`, renommez-le en `UserRegistrationController`.

---

## 🗃️ Entités principales

* `User` : les utilisateurs
* `Event` : les événements
* `Category` : catégorie d'événements
* `Registration` : inscriptions d'utilisateurs à des événements

```bash
php bin/console make:entity Event
php bin/console make:entity Category
php bin/console make:entity Registration
```

---

## 🔄 Génération des CRUD

```bash
php bin/console make:crud Event
php bin/console make:crud Category
php bin/console make:crud Registration
```

---

## ⚙️ Règles de sécurité (fichier `security.yaml`)

```yaml
access_control:
    - { path: ^/event, roles: ROLE_ADMIN }
    - { path: ^/registration, roles: ROLE_USER }
```

Les rôles sont gérés automatiquement selon le profil utilisateur.

---

## ✨ Interface & Design

* Page d'accueil personnalisée avec Bootstrap 5
* Navbar dynamique selon l'état de connexion
* Lien vers la liste des événements, formulaire d’inscription, gestion admin, etc.

---

## 🔁 Fonctionnalités spécifiques

* 🔐 Authentification via formulaire (login/logout)
* 📝 Création de compte utilisateur
* 📆 Affichage conditionnel d’événements actifs/inactifs
* 🎫 Inscription/désinscription à un événement
* ✅ Confirmation d'inscription par un administrateur

---

## 🔄 GitHub : versionnage

```bash
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/<votre-nom-utilisateur>/gestion_evenements.git
git push -u origin main
```

---

## ✅ Structure du Projet

```
gestion_evenements/
├── config/
├── migrations/
├── public/
├── src/
│   ├── Controller/
│   ├── Entity/
│   ├── Form/
│   └── Repository/
├── templates/
│   ├── base.html.twig
│   ├── registration/
│   ├── event/
│   └── category/
├── translations/
├── .env
├── composer.json
└── README.md
```

---

## 📣 Contributions / Rapport

* Projet réalisé dans le cadre du cours Symfony 7 — L3 Centre Informatique
* Encadré par : **Kamano Tamba Étienne**
* Étudiant : **\[Ton Nom]**

---

✅ **Projet Symfony complet, testé et fonctionnel.**

🗓️ **Date limite : 21/06/2025** — Dépôt via le lien Google Forms du professeur.
