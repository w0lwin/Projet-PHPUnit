<?php

require_once("Categorie.php");

class CategorieDAO{
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    // CREATE
    public function ajouterCategorie(Categorie $categorie) {
        $nomCategorie = $categorie->getNomCategorie();
    
        $query = "INSERT INTO categories (nom_categorie) VALUES (:nomCategorie)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nomCategorie', $nomCategorie, PDO::PARAM_STR);
        return $stmt->execute();
    }
    
    // READ
    public function getAllCategories() {
        $query = "SELECT * FROM categories";
        $stmt = $this->db->prepare($query);
        $categories = [];

        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($row as $e){
            $categorie = new Categorie($e['categorie_id'], $e['nom_categorie']);
            $categories[] = $categorie;
        }
        return $categories;
    }

    public function getCategorieById($categorie_Id) {
        $query = "SELECT * FROM categories WHERE categorie_id = :categorieId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':categorieId', $categorie_Id, PDO::PARAM_INT);
    
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Categorie($row['categorie_id'], $row['nom_categorie']);
        } else {
            return null;
        }
    }
    

    // UPDATE
    public function updateCategorie(Categorie $categorie) {
        $categorieId = $categorie->getCategorieId();
        $nouveauNom = $categorie->getNomCategorie();

        $query = "UPDATE categories SET nom_categorie = :nouveauNom WHERE categorie_id = :categorieId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':categorieId', $categorieId, PDO::PARAM_INT);
        $stmt->bindParam(':nouveauNom', $nouveauNom, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // DELETE
    public function deleteCategorie($categorie_Id) {
        $query = "DELETE FROM categories WHERE categorie_id = :categorieId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':categorieId', $categorie_Id, PDO::PARAM_INT);
        return $stmt->execute();
    }


}

?>