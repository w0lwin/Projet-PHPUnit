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
        $ingredients = $_POST['noms'];
        $quantites = $_POST['quantites'];

        // Vérifiez si le nombre d'ingrédients, de quantités et d'unités est le même
        if (count($ingredients) === count($quantites)) {
            $ingredientsRecette = [];
            for ($i = 0; $i < count($ingredients); $i++) {
                $ingredientId = $ingredientDAO->getIngredientIdByNom($ingredients[$i]);
                $quantite = intval($quantites[$i]);
                // Vous pouvez ajouter l'unité ici si nécessaire
                $ingredientsRecette[] = ['ingredient_id' => $ingredientId, 'quantite' => $quantite];
            }

            // Mettre à jour les propriétés modifiables
            $existingRecette->setNomRecette($nomRecette);
            $existingRecette->setDifficulte($difficulte);
            $existingRecette->setInstructions($instructions);
            $existingRecette->setTempsPreparation($tempsPreparation);
            $existingRecette->setTempsCuisson($tempsCuisson);
            $existingRecette->setIngredientsRecette($ingredientsRecette);

            // Mettre à jour la recette dans la base de données
            $recetteDAO->updateRecette($existingRecette);

            // Redirigez vers la page d'index ou une autre page après la mise à jour
            header('Location: ../index.php');
        } else {
            // Gérez l'erreur si le nombre d'ingrédients ne correspond pas au nombre de quantités
            echo "Le nombre d'ingrédients ne correspond pas au nombre de quantités.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

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
            <input type="text" id="nomRecette" name="nomRecette" value="<?php echo $recette->getNomRecette(); ?>" required>

            <label for="difficulte">Difficulté :</label>
            <input type="text" id="difficulte" name="difficulte" value="<?php echo $recette->getDifficulte(); ?>" required>

            <label for="instructions">Instructions :</label>
<textarea name="instructions" id="instructions" cols="30" rows="10" required><?php echo $recette->getInstruction(); ?></textarea>

            <label for="tempsPreparation">Temps de préparation :</label>
            <input type="text" id="tempsPreparation" name="tempsPreparation" value="<?php echo $recette->getTempsPreparation(); ?>" required>

            <label for="tempsCuisson">Temps de cuisson :</label>
            <input type="text" id="tempsCuisson" name="tempsCuisson" value="<?php echo $recette->getTempsCuisson(); ?>" required>

            <!-- Section pour afficher les ingrédients actuels -->
            <label for="ingredientsActuels">Ingrédients actuels :</label>
            <ul>
                <?php foreach ($ingredients as $ingredient): ?>
                    <?php
                    $id = $ingredient['id']; 
                    $id = intval($id);
                    $getIngredient = $ingredientDAO->getIngredientsById($id);
                    ?>
                    <li>
                        <!-- Champ d'entrée pour la modification du nom -->
                        <label for="nom_<?php echo $id; ?>">Nom:</label>
                        <input type="text" name="noms[]" id="nom_<?php echo $id; ?>" value="<?php echo $getIngredient->getNomIngredient(); ?>">

                        <!-- Champ d'entrée pour la modification de la quantité -->
                        <label for="quantite_<?php echo $id; ?>">Quantité:</label>
                        <input type="text" name="quantites[]" id="quantite_<?php echo $id; ?>" value="<?php echo $ingredient['quantite']; ?>">
                    </li>
                <?php endforeach; ?>
            </ul>

            <input type="submit" name="submit" value="Valider les modifications">
        </form>
    <?php else : ?>
        <p>Aucune recette trouvée.</p>
    <?php endif; ?>
</body>

</html>
