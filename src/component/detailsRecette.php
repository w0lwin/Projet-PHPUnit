<?php
require_once '../recette/RecetteDAO.php';
require_once '../config.php';

function details(){
    global $bdd;
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        //convertir en int
        $id = intval($id);

        var_dump($id);
        $recetteDAO = new RecetteDAO($bdd);
        
        // Récupérer la recette
        $recette = $recetteDAO->getRecetteById($id);
        
        // Récupérer les ingrédients de la recette
        $ingredients = $recetteDAO->getIngredientsRecette($id);
        
    }
}
details();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Details recette</title>
</head>
<body>
    <p>Details recette</p>
    <?php if (isset($recette) && !empty($recette)): ?>
        <h2>Details de la recette :</h2>
        <ul>
            <li>
                <h3>Nom: <?php echo $recette->getNomRecette(); ?></h3>
                <p>Difficulté: <?php echo $recette->getDifficulte(); ?></p>
                <p>Temps de préparation: <?php echo $recette->getTempsPreparation(); ?></p>
                <p>Temps de cuisson: <?php echo $recette->getTempsCuisson(); ?></p>
                <p>Instructions: <?php echo $recette->getInstruction(); ?></p>
                <!-- Affiche les différents ingrédients stockés dans $ingredients -->
                <p>Ingrédients: <?php foreach ($ingredients as $ingredient): ?>
                    <p><?php echo $ingredient['nom_ingredient']; ?></p>
                <?php endforeach; ?></p>                    
            </li>
        </ul>
    <?php else: ?>
        <p>Aucune recette trouvée.</p>
    <?php endif; ?>
</body>
</html>
