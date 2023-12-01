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
</head>
<body>
    <div>
        <p>Rechercher une recette</p>
        <form action="" method="post">
            <input type="text" name="shearchBar" id="shearchBar">
            <input type="submit" value="Rechercher">
        </form>
    </div>

    <!-- Affichage des recettes trouvées -->
    <?php if (!empty($recettes)): ?>
        <div>
            <h2>Résultats de la recherche :</h2>
            <ul>
                <?php foreach ($recettes as $recette): ?>
                    <li>
                        <a href="detailsRecette.php?id=<?php echo $recette->getId(); ?>">
                            <h3>Nom: <?php echo $recette->getNomRecette(); ?></h3>
                            <p>Difficulté: <?php echo $recette->getDifficulte(); ?></p>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else: ?>
        <p>Aucune recette trouvée.</p>
    <?php endif; ?>
</body>
</html>
