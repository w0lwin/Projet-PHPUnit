<?php
require_once('Ingredient.php');
// require_once '../config.php';


class IngredientDAO{
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function getIngredientsById($id){

        if($id == null){
            throw new Exception('L\'id de l\'ingrédient est obligatoire');
        }

        if(!is_int($id)){
            throw new Exception('L\'id de l\'ingrédient doit être un nombre');
        }

        if($id < 0){
            throw new Exception('L\'id de l\'ingrédient doit être un nombre positif');
        }

        $sql = "SELECT * FROM ingredients WHERE ingredient_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $ingredient = new Ingredient($result['ingredient_id'], $result['nom_ingredient'], $result['unite_mesure']);
        return $ingredient;
    }

    public function getIngredients(){
        $sql = "SELECT * FROM ingredients";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ingredients = [];
        foreach($result as $row){
            $ingredient = new Ingredient($row['ingredient_id'], $row['nom_ingredient'], $row['unite_mesure']);
            array_push($ingredients, $ingredient);
        }
        return $ingredients;
    }

    public function addIngredient(Ingredient $ingredient){
        $nom_ingredient = $ingredient->getNomIngredient();
        $unite_mesure = $ingredient->getUniteMesure();

        $unites_mesures = ['g', 'kg', 'ml', 'L', 'c. à thé', 'c. à soupe', 'tasse', 'tasse à thé', 'tasse à café'];



        if($nom_ingredient == null || $unite_mesure == null){
            throw new Exception('Le nom de l\'ingrédient et l\'unité de mesure sont obligatoires');
        }

        if(!in_array($unite_mesure, $unites_mesures)){
            throw new Exception('L\'unité de mesure n\'est pas valide');
        }

        $this->getIngredients();
        foreach($this->getIngredients() as $ingredient){
            if($ingredient->getNomIngredient() == $nom_ingredient){
                throw new Exception('L\'ingrédient existe déjà');
            }
        }

        if(!is_string($nom_ingredient) || !is_string($unite_mesure)){
            throw new Exception('Le nom de l\'ingrédient doit être une chaîne de caractères');
        }

        $sql = "INSERT INTO ingredients (nom_ingredient, unite_mesure) VALUES (:nom_ingredient, :unite_mesure)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nom_ingredient', $nom_ingredient);
        $stmt->bindParam(':unite_mesure', $unite_mesure);
        $stmt->execute();

        $ingredient_id = $this->db->lastInsertId();

        return $ingredient_id;
    }

    public function updateIngredient(Ingredient $ingredient){
        $ingredient_id = $ingredient->getIngredientId();
        $nom_ingredient = $ingredient->getNomIngredient();
        $unite_mesure = $ingredient->getUniteMesure();

        $unites_mesures = ['g', 'kg', 'ml', 'L', 'c. à thé', 'c. à soupe', 'tasse', 'tasse à thé', 'tasse à café'];

        if($ingredient_id == null){
            throw new Exception('L\'id de l\'ingrédient, le nom de l\'ingrédient et l\'unité de mesure sont obligatoires');
        }

        if(!is_int($ingredient_id)){
            throw new Exception('L\'id de l\'ingrédient doit être un nombre');
        }

        if($ingredient_id < 0){
            throw new Exception('L\'id de l\'ingrédient doit être un nombre positif');
        }

        if(!in_array($unite_mesure, $unites_mesures)){
            throw new Exception('L\'unité de mesure n\'est pas valide');
        }

        foreach ($this->ingredientDAO->getIngredients() as $existingIngredient) {
            if ($existingIngredient->getNomIngredient() == $nom_ingredient) {
                throw new Exception('L\'ingrédient existe déjà');
            }
        }
        
        if(!is_string($nom_ingredient)){
            throw new Exception('Le nom de l\'ingrédient doit être une chaîne de caractères');
        }

        $sql = "UPDATE ingredients SET nom_ingredient = :nom_ingredient, unite_mesure = :unite_mesure WHERE ingredient_id = :ingredient_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':ingredient_id', $ingredient_id);
        $stmt->bindParam(':nom_ingredient', $nom_ingredient);
        $stmt->bindParam(':unite_mesure', $unite_mesure);
        $stmt->execute();
    }

    public function deleteIngredient($ingredient_id){

        if($ingredient_id == null){
            throw new Exception('L\'id de l\'ingrédient est obligatoire');
        }

        if(!is_int($ingredient_id)){
            throw new Exception('L\'id de l\'ingrédient doit être un nombre');
        }

        if($ingredient_id < 0){
            throw new Exception('L\'id de l\'ingrédient doit être un nombre positif');
        }

        $sql = "DELETE FROM ingredients WHERE ingredient_id = :ingredient_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':ingredient_id', $ingredient_id);
        $stmt->execute();
    }
    

    

    public function getIdByNomIngredient($nomIngredient)
    {

        if($nomIngredient == null){
            throw new Exception('Le nom de l\'ingrédient est obligatoire');
        }

        if(!is_string($nomIngredient)){
            throw new Exception('Le nom de l\'ingrédient doit être une chaîne de caractères');
        }
        
        $stmt = $this->db->prepare("SELECT ingredient_id FROM ingredients WHERE nom_ingredient = :nom_ingredient");
        $stmt->bindParam(':nom_ingredient', $nomIngredient);
        $stmt->execute();
    
        // Fetch retourne un tableau associatif ou un booléen (false si aucune ligne n'est trouvée)
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Vérifier si $result est un tableau et contient la clé 'ingredient_id'
        if ($result && array_key_exists('ingredient_id', $result)) {
            return $result['ingredient_id'];
        } else {
            // Retourner false ou null ou gérer de toute autre manière appropriée
            return false;
        }
    }
    
    


}


?>