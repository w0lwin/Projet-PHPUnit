<?php
// require_once '../config.php';
require_once("Categorie.php");

class CategorieDAO{
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    // CREATE
    public function ajouterCategorie(Categorie $categorie) {
        $nomCategorie = $categorie->getNomCategorie();

        if(!is_string($nomCategorie)){
            throw new Exception('string obligatoire');
        }
        if($nomCategorie == null){
            throw new Exception('champs vide');
        }
    
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

    public function getCategorieById($id)
    {
        if($id == null){
            throw new Exception('L\'id de la catégorie est obligatoire');
        }

        if(!is_int($id)){
            throw new Exception('L\'id de la catégorie doit être un nombre');
        }

        if($id < 0){
            throw new Exception('L\'id de la catégorie doit être un nombre positif');
        }
        $query = "SELECT * FROM categories WHERE categorie_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    
        return new Categorie($result['categorie_id'], $result['nom_categorie']);
    }
    
    

    // UPDATE
    public function updateCategorie(Categorie $categorie) {
        $categorieId = $categorie->getCategorieId();
        $nouveauNom = $categorie->getNomCategorie();
    
        if($categorieId == null || $nouveauNom == null){
            throw new Exception("champ vide");
        }
        if($categorieId < 1){
            throw new Exception("valeur negatif non autorisé");
        }
    
        $query = "UPDATE categories SET nom_categorie = :nouveauNom WHERE categorie_id = :categorieId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':categorieId', $categorieId, PDO::PARAM_INT);
        $stmt->bindParam(':nouveauNom', $nouveauNom, PDO::PARAM_STR);
        $stmt->execute();
    
        // Fetch the updated category from the database
        $updatedCategory = $this->getCategorieById($categorieId);
    
        return $updatedCategory;
    }
    

    // DELETE
    public function deleteCategorie($categorie_Id) {
       

        if($categorie_Id == null){
            throw new Exception('champs vide');
        }

        if(!is_int($categorie_Id)){
            throw new Exception('id doit etre un nombre');
        }

        if($categorie_Id < 0){
            throw new Exception('id doit etre un nombre positif');
        }

        $query = "DELETE FROM categories WHERE categorie_id = :categorieId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':categorieId', $categorie_Id, PDO::PARAM_INT);
        $stmt->execute();
        return null;
    }


}

?>