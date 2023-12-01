
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shearchBar</title>
</head>
<body>
    <p>Rechercher un film</p>
    <form action="front/shearchBar.php" method="post">
        <input type="text" name="shearchBar" id="shearchBar">
        <input type="submit" value="Rechercher">
    </form>
</body>
</html>
    <?php

        $recetteDAO =  GlobalVariable::$recetteDAO;
        if ($recetteDAO !== null) {
            $recetteDAO->getRecetteByTitle($_POST['shearchBar']);
        } else {
            echo "Erreur : objet RecetteDAO non initialisé.";
        }
        

    ?>
