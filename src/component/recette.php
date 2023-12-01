<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../Css/index.css">
</head>
<body>
    <header>
        <a href="../index.php">Home</a>
        <a href="ajout.php">ajout</a>
        <a href="recherche.php">Recherche</a>
    </header>
    <h1>Recette</h1>
    <?php
    require_once('../src/config.php');
    require_once('../src/recette/RecetteDAO.php');
    require_once('../src/recette/Recette.php');
    global $bdd;

    $recetteDAO = new RecetteDAO($bdd);

    $recettes = $recetteDAO->getRecettes();

    if (!empty($recettes)) {
        foreach ($recettes as $recette) {
            echo '<h2>' . $recette->getNomRecette() . '</h2>';
            echo '<p><strong>Instructions:</strong> ' . $recette->getInstruction() . '</p>';
            echo '<p><strong>Temps de préparation:</strong> ' . $recette->getTempsPreparation() . ' minutes</p>';
            echo '<p><strong>Temps de cuisson:</strong> ' . $recette->getTempsCuisson() . ' minutes</p>';
            echo '<p><strong>Difficulté:</strong> ' . $recette->getDifficulte() . '</p>';
        }
    } else {
        echo '<p>Aucune recette trouvée.</p>';
    }
    
    
    ?>
</body>
</html>