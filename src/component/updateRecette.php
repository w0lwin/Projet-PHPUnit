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
    if (isset($_POST['ajouterIngredient'])) {
        $nouvelIngredient = sanitizeInput($_POST['nouvelIngredient']);
        $quantiteNouvelIngredient = intval($_POST['quantiteNouvelIngredient']);

        // convertit en int
        $quantiteNouvelIngredient = intval($quantiteNouvelIngredient);

        // Créer un objet ingrédient avec le nom
        $ingredient = new Ingredient(null, $nouvelIngredient, 'g');

        // Vérifier si l'ingrédient existe déjà
        $existingIngredient = $ingredientDAO->getIdByNomIngredient($nouvelIngredient);

        if (!$existingIngredient) {
            // L'ingrédient n'existe pas encore, ajouter-le à la base de données
            $ingredientId = $ingredientDAO->addIngredient($ingredient);
            $ingredientId = intval($ingredientId);

            echo "L'ingrédient a été ajouté à la base de données";

            // Ajouter l'ingrédient à la recette
            $recetteDAO->addIngredientRecette($existingRecette->getId(), $ingredientId, $quantiteNouvelIngredient);

            // Rediriger vers la même page après l'ajout
            header('Location: updateRecette.php?id=' . $existingRecette->getId());
        } else {
            // L'ingrédient existe déjà, ajouter simplement la quantité
            $recetteDAO->updateIngredientRecette($existingRecette->getId(), $existingIngredient, $quantiteNouvelIngredient);
            // Rediriger vers la même page après l'ajout
            header('Location: updateRecette.php?id=' . $existingRecette->getId());
        }
    }

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
        $deleteIngredients = isset($_POST['delete_ingredients']) ? $_POST['delete_ingredients'] : [];

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

        if ($quantites > 0) {
            // Mettre à jour les ingrédients
            foreach ($ingredients as $ingredient) {
                $ingredientId = $ingredient['id'];
                $index = array_search($ingredientId, array_column($ingredients, 'id'));
                $quantite = $quantites[$index];
                $recetteDAO->updateIngredientsRecette($id, $ingredientId, $quantite);
            }
        }

        // Supprimer les ingrédients marqués pour suppression
        foreach ($deleteIngredients as $ingredientIdToDelete => $deleteFlag) {
            if ($deleteFlag == '1') {
                // Supprimer l'ingrédient seulement s'il reste au moins un ingrédient après la suppression
                if (count($ingredients) - 1 > 0) {
                    $recetteDAO->deleteIngredientRecette($id, $ingredientIdToDelete);
                } else {
                    echo "La recette doit avoir au moins un ingrédient.";
                }
            }
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

                        <!-- Champ caché pour marquer l'ingrédient pour suppression -->
                        <input type="hidden" name="delete_ingredients[<?php echo $id; ?>]" id="delete_ingredient_<?php echo $id; ?>" value="0">

                        <input type="checkbox" name="delete_ingredients[<?php echo $id; ?>]" id="delete_ingredient_<?php echo $id; ?>" value="1">
                        <label for="delete_ingredient_<?php echo $id; ?>">Supprimer cet ingrédient</label>
                    </li>
                <?php endforeach; ?>
            </ul>

            <input type="submit" name="submit" value="Valider les modifications">
        </form>

        <!-- Ajout du formulaire d'ajout d'ingrédients -->
        <form action="updateRecette.php?id=<?php echo $recette->getId(); ?>" method="post">
            <h3>Ajouter un nouvel ingrédient :</h3>
            <label for="nouvelIngredient">Nom de l'ingrédient :</label>
            <input type="text" id="nouvelIngredient" name="nouvelIngredient" required>

            <label for="quantiteNouvelIngredient">Quantité :</label>
            <input type="text" id="quantiteNouvelIngredient" name="quantiteNouvelIngredient" required>

            <input type="submit" name="ajouterIngredient" value="Ajouter">
        </form>

        <?php 
            update($recetteDAO, $ingredientDAO, $recette); 
        ?>
        <!-- Script JavaScript pour la suppression côté client -->
        <script>
            function deleteIngredient(ingredientId) {
                // Marquer l'ingrédient pour la suppression
                document.getElementById('delete_ingredient_' + ingredientId).checked = true;
            }
        </script>

    <?php else : ?>
        <p>Aucune recette trouvée.</p>
    <?php endif; ?>
</body>

</html>
