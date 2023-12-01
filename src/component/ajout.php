<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../Css/index.css">
</head>
<body>
    <header>
        <a href="../index.php">Home</a>
        <a href="recherche.php">ajout</a>
        <a href="recette.php">recette</a>
    </header>
    <h1>Ajouter une recette</h1>

    <?php
    require_once '../recette/RecetteDAO.php';
    require_once '../config.php';
    require_once '../ingredient/IngredientDAO.php';

    // Vérifier si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Créer une instance de RecetteDAO
            $recetteDAO = new RecetteDAO($bdd);

            
            // Créer une instance de IngredientDAO
            $ingredientDAO = new IngredientDAO($bdd);

            // Récupérer les données du formulaire
            $nom_recette = $_POST['nom_recette'];
            $instruction = $_POST['instruction'];
            $temps_preparation = isset($_POST['temps_preparation']) ? (int)$_POST['temps_preparation'] : null;
            $temps_cuisson = isset($_POST['temps_cuisson']) ? (int)$_POST['temps_cuisson'] : null;
            $difficulte = isset($_POST['difficulte']) ? (int)$_POST['difficulte'] : null;
            $categorie_id = isset($_POST['categorie_id']) ? (int)$_POST['categorie_id'] : null;
            $ingredients = $_POST['ingredients'];
            $quantites = $_POST['quantite']; // Nouveau tableau pour stocker les quantités
            

            // Vérifier si la valeur est bien un entier
            if (!is_int($temps_preparation)) {
            throw new InvalidArgumentException('La valeur de temps_preparation n\'est pas un entier.');
            }
            if (!is_int($temps_cuisson)) {
            throw new InvalidArgumentException('La valeur de temps_cuisson n\'est pas un entier.');
            }
            if (!is_int($difficulte)) {
            throw new InvalidArgumentException('La valeur de difficulté n\'est pas un entier.');
            }

            // Créer une instance de Recette avec les données du formulaire
            $recette = new Recette(
                null,
                $nom_recette,
                $instruction,
                $temps_preparation,
                $temps_cuisson,
                $difficulte,
                $categorie_id,
                $ingredients,
                $quantites // Ajouter le tableau des quantités
            );
            var_dump($recette);

            // Ajouter la recette à la base de données
            $recetteDAO->addRecette($recette, $quantite);

            echo '<p>Recette ajoutée avec succès!</p>';
        } catch (Exception $e) {
            echo '<p>Erreur lors de l\'ajout de la recette: ' . $e->getMessage() . '</p>';
        }
    }
    ?>

    <form method="post" action="">
        <!-- Ajoutez les champs du formulaire nécessaires ici -->
        <!-- Exemple : -->
        <label for="nom_recette">Nom de la recette:</label>
        <input type="text" name="nom_recette" required>
        
        <label for="instruction">Instructions:</label>
        <textarea name="instruction" required></textarea>
        
        <label for="temps_preparation">Temps de préparation (en minutes):</label>
        <input type="number" name="temps_preparation" required>
        
        <label for="temps_cuisson">Temps de cuisson (en minutes):</label>
        <input type="number" name="temps_cuisson" required>
        
        <label for="difficulte">Difficulté:</label>
        <select name="difficulte" required>
            <option value="1">Facile</option>
            <option value="2">Moyenne</option>
            <option value="3">Difficile</option>
        </select>
        
        <label for="categorie_id">Catégorie:</label>
        <!-- Remplacez le champ de sélection par votre liste de catégories -->
        <select name="categorie_id" required>
            <option value="1">Catégorie 1</option>
            <option value="2">Catégorie 2</option>
            <!-- ... -->
        </select>
        
        <label for="ingredients">Ingrédients </label>
        <input type="text" name="ingredients" required>
        </select>
        <input type="submit" value="Ajouter la recette">
        
    </form>
</body>
</html>