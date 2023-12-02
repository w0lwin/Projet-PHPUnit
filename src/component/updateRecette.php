<?php
require_once '../recette/RecetteDAO.php';
require_once '../ingredient/IngredientDAO.php';
require_once '../config.php';

global $bdd;

$recetteDAO = new RecetteDAO($bdd);
$ingredientDAO = new IngredientDAO($bdd);

function getRecette($recetteDAO, $ingredientDAO)
{
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        // convertir en int
        $id = intval($id);

        // Récupérer la recette
        $recette = $recetteDAO->getRecetteById($id);
        return $recette;
    }
}

function update($recetteDAO, $ingredientDAO, $existingRecette)
{
    if (isset($_POST['submit'])) {
        $id = $_GET['id'];
        $id = intval($id);
        $nomRecette = $_POST['nomRecette'];
        $difficulte = $_POST['difficulte'];
        $instructions = $_POST['instructions'];
        $tempsPreparation = $_POST['tempsPreparation'];
        $tempsCuisson = $_POST['tempsCuisson'];
        $noms = isset($_POST['noms']) ? $_POST['noms'] : [];
        $quantites = isset($_POST['quantites']) ? $_POST['quantites'] : [];

        $tempsPreparation = intval($_POST['tempsPreparation']);
        $tempsCuisson = intval($_POST['tempsCuisson']);
        $difficulte = intval($_POST['difficulte']);
        $quantites = array_map('intval', $quantites);

        // Récupérer les ingrédients actuels de la recette
        $ingredients = $recetteDAO->getIngredientsRecette($id);

        // Mettre à jour les propriétés modifiables
        $existingRecette->setNomRecette($nomRecette);
        $existingRecette->setDifficulte($difficulte);
        $existingRecette->setInstruction($instructions);
        $existingRecette->setTempsPreparation($tempsPreparation);
        $existingRecette->setTempsCuisson($tempsCuisson);

        $recetteDAO->updateRecette($existingRecette);

        // Mettre à jour les ingrédients
        foreach ($ingredients as $ingredient) {
            $ingredientId = $ingredient['id'];
            $index = array_search($ingredientId, array_column($ingredients, 'id'));
            $quantite = $quantites[$index];       
            $recetteDAO->updateIngredientsRecette($id, $ingredientId, $quantite);
        }

        // header('Location: /detailsRecette.php?id=' . $id);
    }
}

   




function sanitizeInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une recette</title>
</head>

<body>
    <?php
    $recette = getRecette($recetteDAO, $ingredientDAO);
    if (!empty($recette)) :
        // Récupérer les ingrédients de la recette
        $ingredients = $recetteDAO->getIngredientsRecette($recette->getId());
    ?>
        <h2>Modifier la recette :</h2>
        <form action="updateRecette.php?id=<?php echo $recette->getId(); ?>" method="post">
            <label for="nomRecette">Nom de la recette :</label>
            <input type="text" id="nomRecette" name="nomRecette" value="<?php echo sanitizeInput($recette->getNomRecette()); ?>" required>

            <label for="difficulte">Difficulté :</label>
            <input type="text" id="difficulte" name="difficulte" value="<?php echo sanitizeInput($recette->getDifficulte()); ?>" required>

            <label for="instructions">Instructions :</label>
            <textarea name="instructions" id="instructions" cols="30" rows="10" required><?php echo sanitizeInput($recette->getInstruction()); ?></textarea>

            <label for="tempsPreparation">Temps de préparation :</label>
            <input type="text" id="tempsPreparation" name="tempsPreparation" value="<?php echo sanitizeInput($recette->getTempsPreparation()); ?>" required>

            <label for="tempsCuisson">Temps de cuisson :</label>
            <input type="text" id="tempsCuisson" name="tempsCuisson" value="<?php echo sanitizeInput($recette->getTempsCuisson()); ?>" required>

            <label for="ingredientsActuels">Ingrédients actuels :</label>
            <ul>
                <?php foreach ($ingredients as $ingredient): ?>
                    <?php
                    $id = $ingredient['id'];
                    $id = intval($id);
                    $getIngredient = $ingredientDAO->getIngredientsById($id);
                    ?>
                    <li>
                        <p><?php echo $getIngredient->getNomIngredient(); ?></p>

                        <label for="quantite_<?php echo $id; ?>">Quantité:</label>
                        <input type="text" name="quantites[]" id="quantite_<?php echo $id; ?>" value="<?php echo isset($ingredient['quantite']) ? intval($ingredient['quantite']) : ''; ?>" required>
                    </li>
                <?php endforeach; ?>
            </ul>

            <input type="submit" name="submit" value="Valider les modifications">
        </form>
        <?php 
            update($recetteDAO, $ingredientDAO, $recette); 
            
        ?>
    <?php else : ?>
        <p>Aucune recette trouvée.</p>
    <?php endif; ?>
</body>

</html>
