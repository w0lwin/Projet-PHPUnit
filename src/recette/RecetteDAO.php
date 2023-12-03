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
    
        $ingredients = $this->getIngredientsRecette($id);
        $ingredientIds = [];
    
        foreach ($ingredients as $ingredient) {
            $ingredientIds[] = $ingredient['id'];
        }
    
        $recette = new Recette(
            $result['id'],
            $result['nom_recette'],
            $result['instruction'],
            $result['temps_preparation'],
            $result['temps_cuisson'],
            $result['difficulte'],
            $result['categories_id'],
            $ingredientIds
        );
    
        return $recette;
    }
    

    public function searchRecettes($searchTerm) {
        if ($searchTerm == null){
            throw new InvalidArgumentException('searchTerm should not be null');
        }
    
        if (!is_string($searchTerm)){
            throw new InvalidArgumentException('searchTerm should be a string');
        }
    
        $stmt = $this->pdo->prepare("SELECT DISTINCT r.*
        FROM recettes r
        LEFT JOIN recette_ingredients ri ON r.id = ri.recette_id
        LEFT JOIN ingredients i ON ri.ingredient_id = i.ingredient_id
        LEFT JOIN categories c ON r.categories_id = c.categorie_id
        WHERE r.nom_recette LIKE :searchTerm 
           OR c.nom_categorie LIKE :searchTerm
           OR i.nom_ingredient LIKE :searchTerm");

        $searchTermWithWildcard = "%$searchTerm%";
        $stmt->execute(['searchTerm' => $searchTermWithWildcard]);

 
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $recettes = [];
        foreach ($results as $result) {
            $id = intval($result['id']);
    
            $ingredients = $this->getIngredientsRecette($id);
            $ingredientIds = [];
            foreach ($ingredients as $ingredient) {
                $ingredientIds[] = $ingredient['id'];
            }
    
            $recette = new Recette(
                $id,
                $result['nom_recette'],
                $result['instruction'],
                $result['temps_preparation'],
                $result['temps_cuisson'],
                $result['difficulte'],
                $result['categories_id'],
                $ingredientIds
            );
    
            array_push($recettes, $recette);
        }
    
        return $recettes;
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
            $ingredient = ['id' => $row['ingredient_id'], 'quantite' => $row['Quantite'], 'recette_id'=> $row['recette_id']];
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
        $ingredients = $recette->getIngredients();
        
        if ($nom_recette == null || $instruction == null || $temps_preparation == null || $difficulte == null || $categorie_id == null || $ingredients == null ){
            throw new InvalidArgumentException('instruction, temps_preparation, temps_cuisson, difficulte, categorie_id, nom_recette, ingredients should not be null');
        }
    
        if (!is_int($temps_preparation) || !is_int($temps_cuisson) || !is_int($difficulte) || !is_int($categorie_id)) {
            throw new InvalidArgumentException('temps_preparation, temps_cuisson, and difficulte should be integers');
        }

        if (!is_string($nom_recette) || !is_string($instruction)) {
            throw new InvalidArgumentException('nom_recette and instruction should be strings');
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
    
        return $recetteId;
    }
    
    public function updateRecette(Recette $recette)
    {
        $id = $recette->getId();
        $nom_recette = $recette->getNomRecette();
        $instruction = $recette->getInstruction();
        $temps_preparation = $recette->getTempsPreparation();
        $temps_cuisson = $recette->getTempsCuisson();
        $difficulte = $recette->getDifficulte();
        $categorie_id = $recette->getCategorieId();
        $ingredients = $recette->getIngredients();
    
        if ($nom_recette == null || $instruction == null || $temps_preparation == null || $temps_cuisson == null || $difficulte == null || $categorie_id == null || $ingredients == null ){
            throw new InvalidArgumentException('id, nom_recette, instruction, temps_preparation, temps_cuisson, difficulte, categorie_id, and ingredients should not be null');
        }
    
        if (!is_int($temps_preparation) || !is_int($temps_cuisson) || !is_int($difficulte)) {
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
    
    public function deleteIngredientRecette($recetteId, $ingredientId)
    {
        if ($recetteId == null || $ingredientId == null){
            throw new InvalidArgumentException('recetteId and ingredientId should not be null');
        }
        
        if (!is_int($recetteId) || !is_int($ingredientId)) {
            throw new InvalidArgumentException('recetteId and ingredientId should be integers');
        }
        
        $stmt = $this->pdo->prepare("DELETE FROM recette_ingredients WHERE recette_id = :recette_id AND ingredient_id = :ingredient_id");
        $stmt->bindParam(':recette_id', $recetteId);
        $stmt->bindParam(':ingredient_id', $ingredientId);
        $stmt->execute();
    }
    
    public function updateIngredientsRecette($recetteId, $ingredientId, $quantite)
    {
    
        if ($recetteId == null || $ingredientId == null || $quantite == null){
            throw new InvalidArgumentException('recetteId, ingredientId, and quantite should not be null');
        }
    
        if (!is_int($recetteId) || !is_int($ingredientId) || !is_int($quantite)) {
            throw new InvalidArgumentException('recetteId, ingredientId, and quantite should be integers');
        }
    
        $stmt = $this->pdo->prepare("UPDATE recette_ingredients SET quantite = :quantite WHERE recette_id = :recette_id AND ingredient_id = :ingredient_id");
        $stmt->bindParam(':recette_id', $recetteId);
        $stmt->bindParam(':ingredient_id', $ingredientId);
        $stmt->bindParam(':quantite', $quantite);
        $stmt->execute();
    }
    
    public function addIngredientRecette($recetteId, $ingredientId, $quantite)
    {
    
        if ($recetteId == null || $ingredientId == null || $quantite == null){
            throw new InvalidArgumentException('recetteId, ingredientId, and quantite should not be null');
        }
    
        if (!is_int($recetteId) || !is_int($ingredientId) || !is_int($quantite)) {
            throw new InvalidArgumentException('recetteId, ingredientId, and quantite should be integers');
        }
    
        $stmt = $this->pdo->prepare("INSERT INTO recette_ingredients (recette_id, ingredient_id, quantite) VALUES (:recette_id, :ingredient_id, :quantite)");
        $stmt->bindParam(':recette_id', $recetteId);
        $stmt->bindParam(':ingredient_id', $ingredientId);
        $stmt->bindParam(':quantite', $quantite);
        $stmt->execute();
    }
    
}

?>

