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

// fonction pour donner une image selon la categorie de la recette
function getImageForCategory($categoryId) {
    // Logique pour associer une image à l'aide d'un tableau associatif

    $categoryImages = array(
        2 => 'https://img.freepik.com/photos-gratuite/pates-penne-sauce-tomate-au-poulet-tomates-table-bois_2829-19744.jpg?size=626&ext=jpg&ga=GA1.1.1607612590.1695717085&semt=ais',
        3 => 'https://img.freepik.com/photos-gratuite/femme-mange-gateau-cacao-couches-creme-blanche-morceaux-chocolat_141793-2217.jpg?size=626&ext=jpg&ga=GA1.1.1607612590.1695717085&semt=ais',
        6 => 'https://img.freepik.com/photos-premium/antipasto-espagnol-classique-pintxos-tapas-aux-crevettes-camembert-saumon-jambon-dans-assiette-blanche-mise-au-point-selective_207126-4362.jpg?size=626&ext=jpg&ga=GA1.1.1607612590.1695717085&semt=ais',
        10 => 'https://img.freepik.com/photos-gratuite/serveur-met-pailles-plastique-dans-cocktail-sangria-dans-verre_140725-1476.jpg?size=626&ext=jpg&ga=GA1.1.1607612590.1695717085&semt=ais',
        12 => 'https://img.freepik.com/photos-gratuite/assiette-crevettes-garnie-epinards-farcis-pain-sauce-carottes-rapees_141793-2275.jpg?size=626&ext=jpg&ga=GA1.1.1607612590.1695717085&semt=ais',
    );

    // Vérifiez si la catégorie existe dans le tableau, sinon utilisez une image par défaut
    return isset($categoryImages[$categoryId]) ? $categoryImages[$categoryId] : 'default_image.jpg';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Details recette</title>
    <link rel="stylesheet" href="../../Css/index.css">
</head>
<body>
<header>
        <a href="../../index.php">Home</a>
        <a href="displayRecette.php">Recettes</a>
        <a href="ajout.php">ajout</a>
        <a href="shearchBar.php">Recherche</a>
    </header>
    <?php if (!empty($recette)): ?>
        <h2>Details de la recette :</h2>
        <ul>
            <li>
                <?php
                    $imagePath = getImageForCategory($recette->getCategorieId());
                ?>
                <div class="shearch-image-recette"><img src="<?php echo $imagePath; ?>" alt="image"></div>
                <p>Nom: <?php echo $recette->getNomRecette(); ?></p>
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
