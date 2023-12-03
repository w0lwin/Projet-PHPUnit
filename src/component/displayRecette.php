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
        <a href="ajout.php">ajout</a>
        <a href="shearchBar.php">Recherche</a>
    </header>
    <h1>Recette</h1>

    
    <div class="container">
        <?php
            require_once('../config.php');
            require_once('../recette/RecetteDAO.php');
            global $bdd;

            $recetteDAO = new RecetteDAO($bdd);

            $recettes = $recetteDAO->getRecettes();

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

            if (!empty($recettes)) {
                foreach ($recettes as $recette) {
                    echo '<div class="recette">';
                    echo '<a class="redirection" href="detailsRecette.php?id=' . $recette->getId() . '">';
                    echo '<h2>' . $recette->getNomRecette() . '</h2>';
                    $imagePath = getImageForCategory($recette->getCategorieId());
                    echo '<div class="image-recette"><img src="' . $imagePath . '" alt="image"></div>';
                    echo '</a>';
                    echo '<div class="info-recette">';
                    echo '<p><strong>Temps de préparation:</strong><br> ' . $recette->getTempsCuisson() . 'minutes</p>';
                    echo '<p><strong>Difficulté:</strong><br> ' . $recette->getDifficulte() . '</p>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>Aucune recette trouvée.</p>';
            }
        ?>

    </div>
</body>
</html>