<?php
require_once '../recette/RecetteDAO.php';
require_once '../config.php';

function shearch(){
    global $bdd;

    $recettes = []; // Initialisez $recettes ici

    if (isset($_POST['shearchBar'])) {
        $shearchBar = $_POST['shearchBar'];

        $recetteDAO = new RecetteDAO($bdd);

        // Récupérer les recettes trouvées
        $recettes = $recetteDAO->getRecetteByTitle($shearchBar);
    }

    return $recettes;
}

$recettes = shearch();

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shearchBar</title>
    <link rel="stylesheet" href="../../Css/index.css">
</head>
<body>
    
    <header>
        <a href="../../index.php">Home</a>
        <a href="ajout.php">ajout</a>
        <a href="displayRecette.php">recette</a>
    </header>
    <div>
        <p>Rechercher une recette</p>
        <form action="" method="post">
            <input type="text" name="shearchBar" id="shearchBar">
            <input type="submit" value="Rechercher">
        </form>
    </div>

    <!-- Affichage des recettes trouvées -->
    <?php if (!empty($recettes)): ?>
        <div class="shearch">
            <h2>Résultats de la recherche :</h2>
            <div class="list">
                <ul class="ul-list" >
                    <?php foreach ($recettes as $recette): ?>
                        <li class="li-list">
                            <a href="detailsRecette.php?id=<?php echo $recette->getId(); ?>">
                            <?php
                            $imagePath = getImageForCategory($recette->getCategorieId());
                            ?>
                            <div class="shearch-image-recette"><img src="<?php echo $imagePath; ?>" alt="image"></div>
                                <div class="info-shearch-recette">
                                    <p>Nom:<br> <?php echo $recette->getNomRecette(); ?></p>
                                    <p>Difficulté:<br> <?php echo $recette->getDifficulte(); ?></p>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php else: ?>
        <p>Aucune recette trouvée.</p>
    <?php endif; ?>
</body>
</html>
