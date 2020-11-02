
# Learning symfony for robotic project 

Ce projet en cours de développement a pour objectif d'exploiter le potentiel du ***framework Symfony.***

## Partie Front-end
J'ai intègré la partie front-end du site publié sur les GitHub Pages [**ihm-robotique**](https://ricou12.github.io/Robotique/) développé avec Jekyll, un générateur de site statique.

Sur ce premier projet je m'étais consacré à la rédaction du contenu, en exploitant différents langages et technologies.

 - HTML, Bootstrap, approfondissement de SASS, JavaScript, utilisation d'un langage de templating (liquid).
 - Optimisation du SEO pour le référencement sur les moteurs de recherches.

## Partie Back-end
J'ai ajouté différentes fonctionnalités pour la partie back-end.

 - **Envoi d’e-mail** via un formulaire de contact en utilisant le SMTP de Google.
	 - Création d'un compte Gmail
	 - Sécurisation du compte Gmail
	 - Ajout d'un code de sécurité pour les applications
	 - Création du formulaire avec le composant form
	 - Envoi des données avec Mailer
	 - Sauvegarde des informations dans une table de la base de données.
 - Création d'un **forum**
	 - Réalisation du schéma de la base de données en intégrant les cardinalités (associations entre les entités avec l'outils jMerise).
	 - Création de la base de données et génération des entity avec la console.
	 - Mise en place du système d'authentification sécurisé pour  l'enregistrement et la connexion des utilisateurs.
	 - Développement des interfaces clients.
	 - Développement du back office pour l’administration des comptes , sujets et commentaires.
	 - Intégration de la pagination via un bundle.

## Installation

 1. **Prérequis**: Vous devez avoir installer [**wampServer**](https://www.wampserver.com/).

 2. **Télécharger le projet** dans le dossier *www* de *wampserver* soit à partir du zip ou avec la commande  **git** via l'invite de commande.

	    git clone https://github.com/ricou12/learning-symfony-for-project-robotic.git
		 
 3. **Mettre à jour les dépendances du projet** à partir du terminal de votre éditeur de code avec la commande:

		   composer install

 4. **Transpilation du code SCSS et intégration des assets** avec le bundle Webpack Encore, pour cela utiliser la commande:

	    yarn watch
	ou si vous préféré le gestionnaire de package NPM

	    npm run watch

 5. **Créer la base de données**
	 Vérifier et modifier si nécessaire le numéro port utilisé par mysql, dans le fichier de configuration app/**.env**.  (variable "DATABASE_URL=" )puis creer la base de données:

	    php bin/console doctrine:database:create

 7. **Importer le schema de la base de données**
 

	    php bin/console doctrine:migrations:migrate 

 8. Vous pouvez enfin **exécuter le projet** en lançant simplement le serveur fourni par symfony ou bien avec le serveur apache.
	Avec **Symfony** utiliser la commande:
	

	    symfony server:start
	  
	 Avec **Apache** vous devez créer un serveurs virtuels, pour cela cliquer sur l'icone Wampserver dans la barre des taches sélectionner vos ***VirtualHosts -> gestion VirtualHosts*** et ajouter un nom et le chemin du projet (**attention** il s'agit de cibler le dossier public qui contient le fichier index.php).
	 Ensuite il faut modifier la configuration  du fichier ***httpd-vhost.conf*** , accessible la aussi avec l’icône Wampserver dans la barre des taches ***apache->httpd-vhost.conf***.
	  En effet il faut indiquer à apache de rediriger les requête vers le controller frontal car c'est lui qui intègre le système de routage.
	  Mais avant cela vérifier que le module d'apache ***rewrite_module*** est activé, ce qui est le cas lors de l'installation par défaut.
	  Enfin, modifier la directive comme suit:
	 

		 <VirtualHost *:80>
			ServerName nom_donnée_au_projet # Ex:  ihm-robotique-symfony
			DocumentRoot chemin_du_projet # Ex: "c:/wamp64/www/symfony/ihm-robotique/public"
			DirectoryIndex index.php
		  	Alias /sf /$data_dir/symfony/web/sf
			<Directory  "c:/wamp64/www/symfony/ihm-robotique/public">
			 <IfModule mod_rewrite.c>
		            Options -MultiViews
		            RewriteEngine On
		            RewriteCond %{REQUEST_FILENAME} !-f
		            RewriteRule ^(.*)$ index.php [QSA,L]
		         </IfModule>
			 Options +Indexes +Includes +FollowSymLinks +MultiViews
			 AllowOverride All
			 Allow from All
			</Directory>
		</VirtualHost>

	 





