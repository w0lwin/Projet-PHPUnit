<?php


class IngredientDAO{
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function getIngredientsById($id){
        $sql = "SELECT * FROM ingredient WHERE ingredient_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $ingredient = new Ingredient($result['ingredient_id'], $result['nom_ingredient'], $result['unite_mesure'], $result['prix']);
        return $ingredient;
    }

    public function addIngredient(Ingredient $ingredient){
        $nom_ingredient = $ingredient->getNomIngredient();
        $unite_mesure = $ingredient->getUniteMesure();
        $prix = $ingredient->getPrix();

        $sql = "INSERT INTO ingredient (nom_ingredient, unite_mesure, prix) VALUES (:nom_ingredient, :unite_mesure, :prix)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nom_ingredient', $nom_ingredient);
        $stmt->bindValue(':unite_mesure', $unite_mesure);
        $stmt->bindValue(':prix', $prix);
        $stmt->execute();
    }

    public function updateIngredient(Ingredient $ingredient){
        $ingredient_id = $ingredient->getIngredientId();
        $nom_ingredient = $ingredient->getNomIngredient();
        $unite_mesure = $ingredient->getUniteMesure();
        $prix = $ingredient->getPrix();
        
        $sql = "UPDATE ingredient SET nom_ingredient = :nom_ingredient, unite_mesure = :unite_mesure, prix = :prix WHERE ingredient_id = :ingredient_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':ingredient_id', $ingredient_id);
        $stmt->bindValue(':nom_ingredient', $nom_ingredient);
        $stmt->bindValue(':unite_mesure', $unite_mesure);
        $stmt->bindValue(':prix', $prix);
        $stmt->execute();
    }

    public function deleteIngredient($ingredient_id){
        $sql = "DELETE FROM ingredient WHERE ingredient_id = :ingredient_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':ingredient_id', $ingredient_id);
        $stmt->execute();
    }

}


?>