# Projet-PHPUnit


Pour pouvoir lancer les test unitaire il faut tout d'abord télécharger le framework à l'aide de cette commande :
composer require --dev phpunit/phpunit

Pour lancer le test unitaire de tout ce qui concerne categorie faites :
./vendor/bin/phpunit ./tests/integration/categorieTest.php

Pour lancer le test unitaire de tout ce qui concerne ingrédient faites :
./vendor/bin/phpunit ./tests/integration/ingredientTest.php

Pour lancer le test unitaire de tout ce qui concerne ingrédient faites :
./vendor/bin/phpunit ./tests/integration/recetteTest.php  

Quelques indications sur le site:
Pour pouvoir ajouter des ingrédient il faut aller dans la modification d'une recette et faire ajouter un nouvelle ingrédient.
Les recherche de recette peuvent prendre en compte le nom, la catégorie et l'ingrédient.