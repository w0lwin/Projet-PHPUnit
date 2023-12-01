
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page principal</title>
</head>
<body>
    <p>Coucou</p>
    <a href="front/shearchBar.php">Rechercher un film</a>
</body>
</html>


<?php
require_once 'config.php';
require_once 'GlobalVariable.php';

GlobalVariable::$recetteDAO = new RecetteDAO($pdo);
GlobalVariable::$ingredientDAO = new IngredientDAO($pdo);
GlobalVariable::$categorieDAO = new CategorieDAO($pdo);

?>