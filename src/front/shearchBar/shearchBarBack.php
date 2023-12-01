<?php
require_once '../../recette/RecetteDAO.php';
require_once '../../config.php';


function shear(){
    if (isset($_POST['shearchBar'])) {
        $shearchBar = $_POST['shearchBar'];
    
        $recetteDAO = new RecetteDAO($bdd);
    
        $resultats = $recetteDAO->getRecetteByTitle($shearchBar);
    
        if ($resultats == null){
            echo "pas de recette trouvée";
            
        }
        else{
            echo "recette trouvée";
        }
        
}
}
?>



