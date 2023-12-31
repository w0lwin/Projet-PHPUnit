<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../Css/index.css">
</head>
<body>
    <header>
        <a href="../../index.php">Home</a>
        <a href="shearchBar.php">recherche</a>
        <a href="displayRecette.php">recette</a>
    </header>
    <h1>Ajouter une recette</h1>

    <?php
    require_once '../recette/RecetteDAO.php';
    require_once '../categorie/CategorieDAO.php';
    require_once '../config.php';

    // Créez une instance de CategorieDAO avec votre connexion à la base de données
    $categorieDAO = new CategorieDAO($bdd);

    // Obtenez toutes les catégories
    $categories = $categorieDAO->getAllCategories();


    // Vérifier si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Créer une instance de RecetteDAO
            $recetteDAO = new RecetteDAO($bdd);

            
            

            // Récupérer les données du formulaire
            $nom_recette = $_POST['nom_recette'];
            $instruction = $_POST['instruction'];
            $temps_preparation = isset($_POST['temps_preparation']) ? (int)$_POST['temps_preparation'] : null;
            $temps_cuisson = isset($_POST['temps_cuisson']) ? (int)$_POST['temps_cuisson'] : null;
            $difficulte = isset($_POST['difficulte']) ? (int)$_POST['difficulte'] : null;
            $categorie_id = isset($_POST['categorie_id']) ? (int)$_POST['categorie_id'] : null;
            $ingredientsStrings = isset($_POST['ingredients']) ? $_POST['ingredients'] : null;
            $quantitesStrings = isset($_POST['quantite']) ? $_POST['quantite'] : null;

            // Convertir les chaînes d'ingrédients et quantite en entiers
            $ingredients = array_map('intval', $ingredientsStrings);

            $quantites = array_map('intval', $quantitesStrings);


            
            $quantitesNotNull = array_filter($quantites, function($quantite) {
                return $quantite !== 0;
            });
            
            // Filtrer les ingrédients et quantités pour exclure ceux sans quantité définie
            $ingredientsWithQuantite = array_filter($ingredients, function($ingredientId) use ($quantites) {
                return isset($quantites[$ingredientId]) && $quantites[$ingredientId] !== 0;
            });

            // Vérifier si au moins un ingrédient est sélectionné avec une quantité
            if (count($ingredientsWithQuantite) === 0) {
                throw new InvalidArgumentException('Veuillez sélectionner au moins un ingrédient avec une quantité.');
            }




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
                $ingredientsWithQuantite,
                $quantitesNotNull 
            );
           

            // Ajouter la recette à la base de données
            $recetteID = $recetteDAO->addRecette($recette, $quantites);
            $recetteID = intval($recetteID);

            foreach ($ingredients as $ingredient) {
                // Récupérer l'ID de l'ingrédient
                $ingredientId = intval($ingredient);

            
                // Vérifier si la quantité correspondante existe dans le tableau des quantités
                if (isset($_POST['quantite'][$ingredientId])) {
                    // Récupérer la quantité associée à l'ingrédient
                    $quantite = intval($_POST['quantite'][$ingredientId]);
                    
                    // Ajouter l'ingrédient à la recette avec sa quantité
                    $recetteDAO->addIngredientRecette($recetteID, $ingredientId, $quantite);
                }
            }
            

            echo '<p>Recette ajoutée avec succès!</p>';
        } catch (Exception $e) {
            echo '<p>Erreur lors de l\'ajout de la recette: ' . $e->getMessage() . '</p>';
        }
    }
    ?>

    <div class="formulaire">
        <form method="post" action="">
            <div class="input-recette">
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
                <select name="categorie_id" required>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?php echo $categorie->getCategorieId(); ?>"><?php echo      $categorie->getNomCategorie(); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

                
            <div class="input-ingredients">
                <h3>Choississez les ingredients necessaire pour votre recette : <br></h3>
                <label for="ingredients"><br>Ingrédients:</label>
                <?php
                    require_once '../ingredient/IngredientDAO.php';

                    // Créer une instance de IngredientDAO
                    $ingredientDAO = new IngredientDAO($bdd);   

                    $ingredients = $ingredientDAO->getIngredients();

                    foreach ($ingredients as $ingredient) {
                        echo '<div>';
                        echo '<input type="checkbox" name="ingredients[]" value="' .        $ingredient->getIngredientId() . '">';
                        echo '<label>' . $ingredient->getNomIngredient() . '</label>';
                        echo '<input type="number" name="quantite[' . $ingredient->getIngredientId() .  ']"  placeholder="Quantité">';
                        echo '</div>';
                    }
                ?>
            </div>
            <input type="submit" value="Ajouter la recette" class="submit-recette">

        </form>
    </div>

</body>
</html>