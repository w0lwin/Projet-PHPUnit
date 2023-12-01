<?php

require_once 'Recette.php';

class RecetteDAO{

    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function getRecetteById($id){

        if ($id == null){
            throw new InvalidArgumentException('id should not be null');
        }

        if (!is_int($id)){
            throw new InvalidArgumentException('id should be an integer');
        }

        $stmt = $this->pdo->prepare("SELECT * FROM recettes WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result == null){
            throw new InvalidArgumentException('no recette with id ' . $id . ' found');
        }

        $ingredients = $this->getIngredientsRecette($id);
        foreach ($ingredients as $ingredient) {
            $ingredients[] = $ingredient['id'];
        }

        $recette = new Recette($result['id'],
        $result['nom_recette'],
        $result['instruction'],
        $result['temps_preparation'],
        $result['temps_cuisson'],
        $result['difficulte'],
        $result['categories_id'],
        $ingredients);

        return $recette;

    }

    public function getRecettes()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM recettes");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $recettes = [];
    
        foreach ($result as $row) {
            // $id = $row['id'];
            $id = intval($row['id']);
    
            $ingredients = $this->getIngredientsRecette($id);
            $ingredientIds = [];
    
            foreach ($ingredients as $ingredient) {
                $ingredientIds[] = $ingredient['id'];
            }
    
            $recette = new Recette(
                $id,
                $row['nom_recette'],
                $row['instruction'],
                $row['temps_preparation'],
                $row['temps_cuisson'],
                $row['difficulte'],
                $row['categories_id'],
                $ingredientIds
            );
    
            array_push($recettes, $recette);
        }
    
        return $recettes;
    }
    
    

    public function getIngredientsRecette($id)
    {
        if ($id == null) {
            throw new InvalidArgumentException('id should not be null');
        }

        if (!is_int($id)) {
            throw new InvalidArgumentException('id should be an integer');
        }
        $stmt = $this->pdo->prepare("SELECT * FROM recette_ingredients WHERE recette_id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ingredients = [];
        foreach ($result as $row) {
            $ingredient = ['id' => $row['ingredient_id'], 'quantite' => $row['Quantite']];
            array_push($ingredients, $ingredient);
        }
        return $ingredients;
    }
    

    public function addRecette(Recette $recette,$quantite)
    {   
        $nom_recette = $recette->getNomRecette();
        $instruction = $recette->getInstruction();
        $temps_preparation = $recette->getTempsPreparation();
        $temps_cuisson = $recette->getTempsCuisson();
        $difficulte = $recette->getDifficulte();
        $categorie_id = $recette->getCategorieId();
        $ingredients = $recette->getIngredients();
        
        if ($nom_recette == null || $instruction == null || $temps_preparation == null || $temps_cuisson == null || $difficulte == null || $categorie_id == null || $ingredients == null ){
            throw new InvalidArgumentException('nom_recette should not be null');
        }
    
        if (!is_int($temps_preparation) || !is_int($temps_cuisson) || !is_int($difficulte)) {
            throw new InvalidArgumentException('temps_preparation, temps_cuisson, and difficulte should be integers');
        }
        
        foreach ($this->getRecettes() as $existingRecette) {
            if ($existingRecette->getNomRecette() == $nom_recette) {
                throw new InvalidArgumentException('nom_recette should be unique');
            }
        }
    
        $stmt = $this->pdo->prepare("INSERT INTO recettes (nom_recette, instruction, temps_preparation, temps_cuisson, difficulte, categories_id) VALUES (:nom_recette, :instruction, :temps_preparation, :temps_cuisson, :difficulte, :categorie_id)");
        $stmt->bindParam(':nom_recette', $nom_recette);
        $stmt->bindParam(':instruction', $instruction);
        $stmt->bindParam(':temps_preparation', $temps_preparation);
        $stmt->bindParam(':temps_cuisson', $temps_cuisson);
        $stmt->bindParam(':difficulte', $difficulte);
        $stmt->bindParam(':categorie_id', $categorie_id);
        $stmt->execute();
    
        // Obtenir l'ID de la recette insérée
        $recetteId = $this->pdo->lastInsertId();
    
        foreach ($ingredients as $ingredient) {
            $ingredientId = $ingredient->getIngredientId(); 
            $index = array_search($ingredientId, array_column($ingredients, 'id'));
            $ingredientQuantite = $quantite[$index];
        
        
            $stmt2 = $this->pdo->prepare("INSERT INTO recette_ingredients (recette_id, ingredient_id, Quantite) VALUES (:recette_id, :ingredient_id, :quantite)");
            $stmt2->bindParam(':recette_id', $recetteId);
            $stmt2->bindParam(':ingredient_id', $ingredientId);
            $stmt2->bindParam(':quantite', $ingredientQuantite);    
            $stmt2->execute();
        }
    }
    
    public function updateRecette(Recette $recette,$quantite)
    {
        $id = $recette->getId();
        $nom_recette = $recette->getNomRecette();
        $instruction = $recette->getInstruction();
        $temps_preparation = $recette->getTempsPreparation();
        $temps_cuisson = $recette->getTempsCuisson();
        $difficulte = $recette->getDifficulte();
        $categorie_id = $recette->getCategorieId();
        $ingredients = $recette->getIngredients();
    
        if ($id == null || $nom_recette == null || $instruction == null || $temps_preparation == null || $temps_cuisson == null || $difficulte == null || $categorie_id == null || $ingredients == null ){
            throw new InvalidArgumentException('id, nom_recette, instruction, temps_preparation, temps_cuisson, difficulte, categorie_id, and ingredients should not be null');
        }
    
        if (!is_int($id) || !is_int($temps_preparation) || !is_int($temps_cuisson) || !is_int($difficulte)) {
            throw new InvalidArgumentException('id, temps_preparation, temps_cuisson, and difficulte should be integers');
        }
    
        $stmt = $this->pdo->prepare("UPDATE recettes SET nom_recette = :nom_recette, instruction = :instruction, temps_preparation = :temps_preparation, temps_cuisson = :temps_cuisson, difficulte = :difficulte, categories_id = :categorie_id WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nom_recette', $nom_recette);
        $stmt->bindParam(':instruction', $instruction);
        $stmt->bindParam(':temps_preparation', $temps_preparation);
        $stmt->bindParam(':temps_cuisson', $temps_cuisson);
        $stmt->bindParam(':difficulte', $difficulte);
        $stmt->bindParam(':categorie_id', $categorie_id);
        $stmt->execute();
    
        $stmt2 = $this->pdo->prepare("DELETE FROM recette_ingredients WHERE recette_id = :id");
        $stmt2->bindParam(':id', $id);
        $stmt2->execute();

    
        foreach ($ingredients as $ingredient) {
            $ingredientId = $ingredient->getIngredientId(); 
            $index = array_search($ingredientId, array_column($ingredients, 'id'));
            $ingredientQuantite = $quantite[$index];
        
        
            $stmt2 = $this->pdo->prepare("INSERT INTO recette_ingredients (recette_id, ingredient_id, Quantite) VALUES (:recette_id, :ingredient_id, :quantite)");
            $stmt2->bindParam(':recette_id', $id);
            $stmt2->bindParam(':ingredient_id', $ingredientId);
            $stmt2->bindParam(':quantite', $ingredientQuantite);    
            $stmt2->execute();
        }
    }

    public function deleteRecette($id)
    {
        if ($id == null){
            throw new InvalidArgumentException('id should not be null');
        }

        if (!is_int($id)) {
            throw new InvalidArgumentException('id should be integer');
        }
        
        $stmt = $this->pdo->prepare("DELETE FROM recettes WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $stmt2 = $this->pdo->prepare("DELETE FROM recette_ingredients WHERE recette_id = :id");
        $stmt2->bindParam(':id', $id);
        $stmt2->execute();
    }
}


?>