<?php

class RecetteDAO{

    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function recetteById($id){
        $stmt = $this->pdo->prepare("SELECT * FROM recette WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return new Recette($row['id'], $row['nom_recette'], $row['instruction'], $row['temps_preparation'], $row['temps_cuisson'], $row['difficulte'], $row['categorie_id']);
    }

    public function addRecette($nom_recette, $instruction, $temps_preparation, $temps_cuisson, $difficulte, $categorie_id){
        $stmt = $this->pdo->prepare("INSERT INTO recette (nom_recette, instruction, temps_preparation, temps_cuisson, difficulte, categorie_id) VALUES (:nom_recette, :instruction, :temps_preparation, :temps_cuisson, :difficulte, :categorie_id)");
        $stmt->execute(['nom_recette' => $nom_recette, 'instruction' => $instruction, 'temps_preparation' => $temps_preparation, 'temps_cuisson' => $temps_cuisson, 'difficulte' => $difficulte, 'categorie_id' => $categorie_id]);
    }

    public function updateRecette($id, $nom_recette, $instruction, $temps_preparation, $temps_cuisson, $difficulte, $categorie_id){
        $stmt = $this->pdo->prepare("UPDATE recette SET nom_recette = :nom_recette, instruction = :instruction, temps_preparation = :temps_preparation, temps_cuisson = :temps_cuisson, difficulte = :difficulte, categorie_id = :categorie_id WHERE id = :id");
        $stmt->execute(['id' => $id, 'nom_recette' => $nom_recette, 'instruction' => $instruction, 'temps_preparation' => $temps_preparation, 'temps_cuisson' => $temps_cuisson, 'difficulte' => $difficulte, 'categorie_id' => $categorie_id]);
    }

    public function deleteRecette($id){
        $stmt = $this->pdo->prepare("DELETE FROM recette WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }


}



?>