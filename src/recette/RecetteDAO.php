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
        $recette = new Recette($result['id'], $result['nom_recette'], $result['instruction'], $result['temps_preparation'], $result['temps_cuisson'], $result['difficulte'], $result['categorie_id'], $result['ingredients']);
        return $recette;
    }

    public function getRecettes(){
        $stmt = $this->pdo->prepare("SELECT * FROM recettes");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $recettes = [];
        foreach ($result as $row) {
            $ingredients = $this->getIngredientsRecette($row['id']);
            foreach ($ingredients as $ingredient) {
                $ingredients[] = $ingredient['id'];
            }
            $recette = new Recette($row['id'], $row['nom_recette'], $row['instruction'], $row['temps_preparation'], $row['temps_cuisson'], $row['difficulte'], $row['categories_id'], $ingredients);
            array_push($recettes, $recette);
        }
        return $recettes;
    }
    

    public function getIngredientsRecette($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM recette_ingredients WHERE recette_id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ingredients = [];
        foreach ($result as $row) {
            $ingredient = ['id' => $row['ingredient_id'], 'quantite' => $row['quantite']];
            array_push($ingredients, $ingredient);
        }
        return $ingredients;
    }
    

    public function addRecette(Recette $recette)
    {   
        $nom_recette = $recette->getNomRecette();
        $instruction = $recette->getInstruction();
        $temps_preparation = $recette->getTempsPreparation();
        $temps_cuisson = $recette->getTempsCuisson();
        $difficulte = $recette->getDifficulte();
        $categorie_id = $recette->getCategorieId();
        
        
        if ($nom_recette == null || $instruction == null || $temps_preparation == null || $temps_cuisson == null || $difficulte == null || $categorie_id == null){
            throw new InvalidArgumentException('nom_recette should not be null');
        }

        if (!is_int($temps_preparation) || !is_int($temps_cuisson) || !is_int($difficulte)) {
            throw new InvalidArgumentException('temps_preparation, temps_cuisson and difficulte should be integers');
        }
        
        foreach ($this->getRecettes() as $recette) {
            if ($recette->getNomRecette() == $nom_recette) {
                throw new InvalidArgumentException('nom_recette should be unique');
            }
        }

        $stmt = $this->pdo->prepare("INSERT INTO recettes (nom_recette, instruction, temps_preparation, temps_cuisson, difficulte, categories_id) VALUES (:nom_recette, :instruction, :temps_preparation, :temps_cuisson, :difficulte, :categorie_id)");
        $stmt->execute(['nom_recette' => $nom_recette, 'instruction' => $instruction, 'temps_preparation' => $temps_preparation, 'temps_cuisson' => $temps_cuisson, 'difficulte' => $difficulte, 'categorie_id' => $categorie_id]);
    
        //recupère l'id de la dernière recette ajouté
        $recette_id = $this->pdo->lastInsertId();
        $ingredients = $recette->getIngredients();

        foreach ($ingredients as $ingredient) {
            $stmt2 = $this->pdo->prepare("INSERT INTO recette_ingredients (recette_id, ingredient_id, quantite) VALUES (:recette_id, :ingredient_id, :quantite)");
            $stmt2->execute(['recette_id' => $recette_id, 'ingredient_id' => $ingredient['id'], 'quantite' => $ingredient['quantite']]);
        }
    }
    

    public function updateRecette($id, $nom_recette, $instruction, $temps_preparation, $temps_cuisson, $difficulte, $categorie_id){
        $stmt = $this->pdo->prepare("UPDATE recettes SET nom_recette = :nom_recette, instruction = :instruction, temps_preparation = :temps_preparation, temps_cuisson = :temps_cuisson, difficulte = :difficulte, categorie_id = :categorie_id WHERE id = :id");
        $stmt->execute(['id' => $id, 'nom_recette' => $nom_recette, 'instruction' => $instruction, 'temps_preparation' => $temps_preparation, 'temps_cuisson' => $temps_cuisson, 'difficulte' => $difficulte, 'categorie_id' => $categorie_id]);
    }

    public function deleteRecette($id){
        $stmt = $this->pdo->prepare("DELETE FROM recettes WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }


}



?>