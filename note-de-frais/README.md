# Note de frais 

Le projet permet de :
- remplir une note de frais en ligne
- ajouter plusieurs lignes de dépense
- joindre des justificatifs
- signer directement depuis l’interface
- enregistrer les données en base
- générer un PDF
- envoyer la note par mail avec le PDF et les justificatifs

## Stack technique

**Front-end** : HTML, CSS, JavaScript vanilla
**Back-end** : PHP / Symfony
**Base de données** : MySQL / Doctrine
**PDF** : Dompdf
**Mail** : Symfony Mailer

## Installation

Cloner le projet puis se placer dans le dossier :

```bash
git clone <url-du-repo>
cd note-de-frais
```

Installer les dépendances PHP :

```bash
composer install
```

## Configuration

Créer un fichier `.env.local` à la racine du projet et y ajouter la configuration locale.

Exemple pour la base de données :

```env
DATABASE_URL="mysql://root:root@127.0.0.1:3306/app?serverVersion=8.0&charset=utf8mb4"
```

Exemple pour le mailer :

```env
MAILER_DSN=gmail+smtp://votre_email%40gmail.com:votre_mot_de_passe_app@default
```

## Base de données

Créer la base de données si nécessaire :

```bash
php bin/console doctrine:database:create
```

Lancer les migrations :

```bash
php bin/console doctrine:migrations:migrate
```

Insérer ensuite les budgets nécessaires dans la table `budget`.

## Lancer le projet

Démarrer le serveur Symfony :

```bash
symfony server:start
```

## Fonctionnalités principales

- formulaire de note de frais
- lignes de dépense dynamiques
- calcul automatique des montants
- justificatif obligatoire
- signature sur canvas
- prévisualisation de la note
- génération de PDF
- envoi par mail avec pièces jointes

## Améliorations plus tard que je ferai

- nettoyage final de certains champs et parties inutiles
- gestion plus propre des utilisateurs
- espace administrateur
- suivi dynamique du budget restant
- amélioration du design et de la mise en page du PDF
- meilleure gestion des statuts de validation


Le dossier `vendor` n’est pas versionné.
n'oubliez pas d'installer :

```bash
composer install
```

avant de pouvoir lancer l’application.
