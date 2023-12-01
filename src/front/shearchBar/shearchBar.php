<?php
require_once '../../recette/RecetteDAO.php';
require_once '../../config.php';

function shearch(){
    global $bdd;

    if (isset($_POST['shearchBar'])) {
        $shearchBar = $_POST['shearchBar'];

        $recetteDAO = new RecetteDAO($bdd);

        // Récupérer les recettes trouvées
        $recettes = $recetteDAO->getRecetteByTitle($shearchBar);
    }
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

    <!-- Affichage des recettes trouvées -->
    <?php if (isset($recettes) && !empty($recettes)): ?>
        <h2>Résultats de la recherche :</h2>
        <ul>
            <?php foreach ($recettes as $recette): ?>
                <li>
                    <h3>Nom: <?php echo $recette->getNomRecette(); ?></h3>
                    <p>ID: <?php echo $recette->getId(); ?></p>
                    <p>Instructions: <?php echo $recette->getInstruction(); ?></p>
                    <p>Temps de préparation: <?php echo $recette->getTempsPreparation(); ?></p>
                    <p>Temps de cuisson: <?php echo $recette->getTempsCuisson(); ?></p>
                    <p>Difficulté: <?php echo $recette->getDifficulte(); ?></p>
                    
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucune recette trouvée.</p>
    <?php endif; ?>
</body>
</html>
<?php } ?>

<?php shearch(); ?>
