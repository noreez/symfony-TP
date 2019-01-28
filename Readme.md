# Instructions

Hello, The following instructions must be followed to use the project: 
* Launch a terminal by positioning itself on the right folder 
* Start the command: docker-compose up-d 
* Run the command: docker-compose exec --user = web application composer install 
* Start the command: docker-compose exec web bash 
* Run the command: php bin/console d:s:u --force 

You must be on port 20000 (127.0.0.1.1.20000) to access the database: 
* user: ipssi
* mdp: ipssi 

An sql file is already predini in the sql-test folder, just import it when connected to the database 

You can also test two commands to create an admin and count the number of videos for a user:
* php bin/app console:create-admin {email} {mdp} 
* php bin / console app:user-count-video {email} 

# Quick Details 

* User: You have to create an account and then log in to edit your account, view and add videos 
* Admin: You need to create an account and then sign in to edit, view and add videos. It can also manage categories and videos. 
_______________________________________________________________________________________________________ 

Bonjour, Il faut suivre les instrucstion suivantes pour utliser le projet: 

* Lancer un terminal en se positionnant sur le bon dossier 
* Lancer la commande: docker-compose up-d 
* Lancer la commande: docker-compose exec --user=application web composer install 
* Lancer la commande: docker-compose exec web bash 
* Lancer la commande: php bin/console d:s:u --force 

Il faut être sur le port 20000 (127.0.0.1:20000) pour accèder à la base de données: 

* user : ipssi
* mdp: ipssi 

Un ficher sql est dejà prédini dans le dossier sql-test, il suffit uniquement de l'importer une fois connecté sur la base de données 

Vous pouvez également tester deux commandes pour créer un admin et compter le nombre de vidéo pour un utilisateur: 

* php bin/console app:create-admin {email} {mdp} 
* php bin/console app:user-count-video {email} 

# Détails Rapide 

* User: Il faut créer un compte et ensuite se connecter pour pouvoir modifier son compte, voir et ajouter des vidéos
* Admin: Il faut créer un compte et ensuite se connecter pour pouvoir modifier, voir et ajouter des vidéos. 
Il peut également gérer les catégories et vidéos.
