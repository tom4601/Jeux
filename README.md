# Plateforme d'Assistance – Projet BTS SIO SISR

Ce projet a été réalisé dans le cadre de la formation **BTS SIO SISR**.  
Il s'agit d'une **plateforme web d'assistance**, développée en PHP avec une base de données MySQL, intégrant un système d'authentification sécurisé et un suivi des tickets.

---

##  Objectif du projet

Permettre à une équipe technique de gérer des tickets d'assistance depuis une interface protégée, avec :
- Un accès restreint par **login / mot de passe**
- Une interface dédiée aux **techniciens** et **administrateurs**
- Une base de données pour centraliser les demandes

---

##  Fonctionnalités principales

-  Authentification sécurisée par session (login + mot de passe)
-  Accès réservé aux **techniciens** et **administrateurs**
-  Htaccess et Htapasswd pour l'accès à l'ajout de technicien
-  Affichage de tous les tickets créés
-  Filtrage par **statut** (`ouvert`, `en cours`, `fermé`)
-  Consultation des tickets résolus
-  **Système de logs** des actions 
-  Base de données SQL
-  Design **responsive** et simple avec W3.CSS

---

## Sécurité intégrée

- Requêtes SQL **préparées**
- Mots de passe **hachés**
- Vérification stricte des sessions et rôles
- Système de **logs** avec horodatage et adresse IP
- Fichier `.env` pour sécuriser les identifiants de connexion

---

## Dépendances

- PHP Mysqli
- MySQL (base de données)
- HTML5 / W3.CSS (responsive)

---

## Installation des dépendances

sudo apt update
apt install apache2 php php-mysqli php-pdo php-mbstring php-xml php-curl php-zip unzip
sudo apt install mysql-server

## Droits du dossier logs

sudo mkdir -p logs
sudo chown www-data:www-data logs
sudo chmod 755 logs

## Modification pour le .htaccess

aller dans votre conf de votre site et ou ce trouve le projet (généralement /var/www/html/votresite) ajouter : 

pour les logs  Dans /logs/.htaccess Deny from all

pour l'ajout d'utilisateur :

AuthType Basic
AuthName "Zone protégée"
AuthUserFile /var/www/html/dev/Ajout_User/.htpasswd
Require valid-user

<FilesMatch "\.(htaccess|htpasswd|env|ini|log|sql|bak)$">
    Require all denied
</FilesMatch>

et après dans il faut  redémarrer apache :

sudo systemctl restart apache2

## Pour le fichier .htpasswd

Utilisez ce générateur en ligne pour créer un utilisateur et mot de passe sécurisés :
htpasswd generator

Copiez ensuite le résultat dans le fichier .htpasswd.


### Démo du projet

https://www.youtube.com/watch?v=bdYLbZCEBjw

