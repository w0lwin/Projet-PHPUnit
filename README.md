# Projet-PHPUnit

gestion de recette
gestion des categories
gestion des ingredients






Faire la supretion des ingrédient
L'ajout d'ingrédient
Leur fonction correspondante dans le test
Le dataProvider


mysqldump -h 91.134.89.49 -P8090 -u appCuissine -p > /Users/teo/appCuisine.sql


mysqldump -h 91.134.89.49 -P 8090 -u appCuissine -p appCuisine> /Users/teo/appCuisine.sql



mysqldump -h 91.134.89.49 -u appCuisine -p -P 8090 appCuisine > /Users/teo/appCuisine.sql



CREATE TABLE `categories` (
  `categorie_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom_categorie` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`categorie_id`)
) 

CREATE TABLE `ingredients` (
  `ingredient_id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_ingredient` varchar(255) DEFAULT NULL,
  `unite_mesure` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ingredient_id`)
) 

CREATE TABLE `recette_ingredients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recette_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `Quantite` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)

CREATE TABLE `recettes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_recette` varchar(255) NOT NULL,
  `instruction` text NOT NULL,
  `temps_preparation` int(11) NOT NULL,
  `temps_cuisson` int(11) NOT NULL,
  `difficulte` int(11) NOT NULL,
  `categories_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)