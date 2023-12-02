# Projet-PHPUnit

gestion de recette
gestion des categories
gestion des ingredients






        $stmt2 = $this->pdo->prepare("DELETE FROM recette_ingredients WHERE recette_id = :id");
        $stmt2->bindParam(':id', $id);
        $stmt2->execute();

            
        foreach ($ingredients as $ingredient) {
            $ingredientId = $ingredient['ingredient_id']; 
            $index = array_search($ingredientId, array_column($ingredients, 'id'));
            $ingredientQuantite = $quantite[$index];
        
            $stmt2 = $this->pdo->prepare("INSERT INTO recette_ingredients (recette_id, ingredient_id, Quantite) VALUES (:recette_id, :ingredient_id, :quantite)");
            $stmt2->bindParam(':recette_id', $id);
            $stmt2->bindParam(':ingredient_id', $ingredientId);
            $stmt2->bindParam(':quantite', $ingredientQuantite);    
            $stmt2->execute();
        }


        UPDATE recette_ingredients SET Quantite = 2 WHERE recette_id = 1 AND ingredient_id = 2;