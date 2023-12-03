<?php
require_once '../recette/RecetteDAO.php';
require_once '../ingredient/IngredientDAO.php';
require_once '../config.php';

global $bdd;
$recetteDAO = new RecetteDAO($bdd);
$ingredientDAO = new IngredientDAO($bdd);

function details($recetteDAO){
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        // convertir en int
        $id = intval($id);
        
        // Récupérer la recette
        $recette = $recetteDAO->getRecetteById($id);

        return $recette; 
    }
}

$recette = details($recetteDAO);
// Récupérer les ingrédients de la recette
$ingredients = $recetteDAO->getIngredientsRecette($recette->getId());
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Details recette</title>
    <link rel="stylesheet" href="../../Css/index.css">
</head>
<body>
    <?php if (!empty($recette)): ?>
        <h2>Details de la recette :</h2>
        <ul>
            <li>
                <h3>Nom: <?php echo $recette->getNomRecette(); ?></h3>
                <p>Difficulté: <?php echo $recette->getDifficulte(); ?></p>
                <!-- Affiche les différents ingrédients stockés dans $ingredients -->
                <p>Ingrédients:</p>
                <ul>
                    <?php foreach ($ingredients as $ingredient): ?>
                        <?php
                        $id = $ingredient['id']; 
                        $id = intval($id);
                        $getIngredient = $ingredientDAO->getIngredientsById($id);
                        ?>
                        <li>
                            <p>Nom: <?php echo $getIngredient->getNomIngredient(); ?></p>
                            <p>Quantité: <?php echo $ingredient['quantite']; ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>  

                <!-- Ajout du bouton de modification -->
                <a href="updateRecette.php?id=<?php echo $recette->getId(); ?>">Modifier la recette</a>
            </li>
        </ul>
    <?php else: ?>
        <p>Aucune recette trouvée.</p>
    <?php endif; ?>
</body>
</html>
