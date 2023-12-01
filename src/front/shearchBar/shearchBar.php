<?php
require_once '../../recette/RecetteDAO.php';
require_once '../../config.php';

// Fonction pour gérer la recherche de recettes
function shearch($bdd){
    if (isset($_POST['shearchBar'])) {
        $shearchBar = $_POST['shearchBar'];

        $recetteDAO = new RecetteDAO($bdd);

        // Récupérer la recette trouvée
        $recetteTrouvee = $recetteDAO->getRecetteByTitle($shearchBar);

        return $recetteTrouvee;
    }
}

// Appel de la fonction shear() pour obtenir la recette trouvée
$recetteTrouvee = shearch($bdd);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shearchBar</title>
</head>
<body>
    <p>Rechercher une recette</p>
    <form action="shearchBar.php" method="post">
        <input type="text" name="shearchBar" id="shearchBar">
        <input type="submit" value="Rechercher">
    </form>

    <!-- Affichage de la recette trouvée -->
    <?php if ($recetteTrouvee !== null): ?>
        <h2>Recette trouvée :</h2>
        <p>ID: <?php echo $recetteTrouvee->getId(); ?></p>
        <p>Nom: <?php echo $recetteTrouvee->getNomRecette(); ?></p>
        <p>Instructions: <?php echo $recetteTrouvee->getInstruction(); ?></p>
        <p>Temps de préparation: <?php echo $recetteTrouvee->getTempsPreparation(); ?></p>
        <p>Temps de cuisson: <?php echo $recetteTrouvee->getTempsCuisson(); ?></p>
        <p>Difficulté: <?php echo $recetteTrouvee->getDifficulte(); ?></p>
    <?php endif; ?>
</body>
</html>
