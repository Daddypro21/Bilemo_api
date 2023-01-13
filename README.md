P7 Openclassrooms -BileMo API


Création d'une API Rest BileMo, une entreprise de vente de téléphone.

Environnement utilisé durant le développement
Symfony 6
PHP 8
Installation
Repo Size
Exécutez la ligne de commande suivante pour télécharger le projet dans le répertoire de votre choix:

git clone https://github.com/Daddypro21/Bilemo_api.git
Installez les dépendances en exécutant la commande suivante:

composer install
Base de données
Modifier la connexion à la base de données dans le fichier .env.

DATABASE_URL=mysql://root:@127.0.0.1:3306/bilemo-api
Créer une base de données:

symfony console doctrine:migrations:migrate
Créez la structure de la base de données:

symfony console doctrine:migrations:migrate
Chargez les données initiales:

symfony console doctrine:fixtures:load
Lancez l'application
Lancez l'environnement d'exécution Apache / Php en utilisant:

php bin/console server:run ou symfony console server:start


Documentation API - Nelmio

Crédits d'utilisateur par défaut
{
    ROLE_USER

  "username": "user@bilemoapi.com",
  "password": "password"

    ROLE_ADMIN

  "username": "admin@bilemoapi.com",
  "password": "password"
}