<?php
require_once '../recette/RecetteDAO.php';
require_once '../config.php';

function shearch(){
    global $bdd;

    $recettes = []; // Initialisez $recettes ici

    if (isset($_POST['shearchBar'])) {
        $shearchBar = $_POST['shearchBar'];

        $recetteDAO = new RecetteDAO($bdd);

        // Récupérer les recettes trouvées
        $recettes = $recetteDAO->getRecetteByTitle($shearchBar);
    }

    return $recettes;
}

$recettes = shearch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shearchBar</title>
    <link rel="stylesheet" href="../../Css/index.css">
</head>
<body>
    
    <header>
        <a href="../../index.php">Home</a>
        <a href="ajout.php">ajout</a>
        <a href="displayRecette.php">recette</a>
    </header>
    <div>
        <p>Rechercher une recette</p>
        <form action="" method="post">
            <input type="text" name="shearchBar" id="shearchBar">
            <input type="submit" value="Rechercher">
        </form>
    </div>

    <!-- Affichage des recettes trouvées -->
    <?php if (!empty($recettes)): ?>
        <div class="shearch">
            <h2>Résultats de la recherche :</h2>
            <div class="list">
                <ul class="ul-list" >
                    <?php foreach ($recettes as $recette): ?>
                        <li class="li-list">
                            <a href="detailsRecette.php?id=<?php echo $recette->getId(); ?>">
                            <div class="shearch-image-recette"><img src="https://assets.afcdn.com/recipe/20160405/45730_w190h190c1cx1000cy1500.webp" alt="image"></div>
                                <div class="info-shearch-recette">
                                    <p>Nom:<br> <?php echo $recette->getNomRecette(); ?></p>
                                    <p>Difficulté:<br> <?php echo $recette->getDifficulte(); ?></p>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php else: ?>
        <p>Aucune recette trouvée.</p>
    <?php endif; ?>
</body>
</html>
