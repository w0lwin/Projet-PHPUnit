<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../Css/index.css">
</head>


<body> 
    <header>
        <a href="../../index.php">Home</a>
        <a href="ajout.php">ajout</a>
        <a href="shearchBar.php">Recherche</a>
    </header>
    <h1>Recette</h1>

    
    <div class="container">
        <?php
            require_once('../config.php');
            require_once('../recette/RecetteDAO.php');
            global $bdd;

            $recetteDAO = new RecetteDAO($bdd);

            $recettes = $recetteDAO->getRecettes();

            if (!empty($recettes)) {
                foreach ($recettes as $recette) {
                    echo '<div class="recette">';
                    echo '<h2>' . $recette->getNomRecette() . '</h2>';
                    echo '<div class="image-recette"><img src="https://assets.afcdn.com/recipe/20160405/45730_w190h190c1cx1000cy1500.webp" alt="image"></div>';
                    echo '<div class="info-recette">';
                    echo '<p><strong>Temps de préparation:</strong><br> ' .  $recette->getTempsCuisson() . ' minutes</p>';
                    echo '<p><strong>Difficulté:</strong><br> ' . $recette->getDifficulte() . '</p>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>Aucune recette trouvée.</p>';
            }
        ?>
    </div>
</body>
</html>