<?php
// require_once '../config.php';
require_once("Categorie.php");

class CategorieDAO{
    // Instance de la base de données (probablement PDO).
    private $db;

    // Constructeur : Initialise la propriété $db avec une instance de la base de données.
    public function __construct($db) {
        $this->db = $db;
    }

    // CREATE
    public function ajouterCategorie(Categorie $categorie) {
        // Récupère le nom de la catégorie depuis l'objet Categorie.
        $nomCategorie = $categorie->getNomCategorie();
    
        // Vérifie si le nom de la catégorie est une chaîne de caractères, sinon, lance une exception.
        if (!is_string($nomCategorie)) {
            throw new Exception('string obligatoire');
        }
    
        // Vérifie si le nom de la catégorie est vide, sinon, lance une exception.
        if ($nomCategorie == null) {
            throw new Exception('champs vide');
        }
    
        // Requête SQL pour l'ajout de la catégorie.
        $query = "INSERT INTO categories (nom_categorie) VALUES (:nomCategorie)";
        
        // Prépare la requête.
        $stmt = $this->db->prepare($query);
        
        // Lie le paramètre nomCategorie à la requête.
        $stmt->bindParam(':nomCategorie', $nomCategorie, PDO::PARAM_STR);
        
        // Exécute la requête et retourne le résultat.
        return $stmt->execute();
    }
    
    // READ
    public function getAllCategories() {
        // Requête SQL pour sélectionner toutes les catégories.
        $query = "SELECT * FROM categories";
        
        // Prépare la requête.
        $stmt = $this->db->prepare($query);
    
        // Initialise un tableau pour stocker les catégories.
        $categories = [];
    
        // Exécute la requête.
        $stmt->execute();
    
        // Récupère toutes les lignes résultantes en tant qu'associatif.
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Parcours chaque ligne résultante pour créer des objets Categorie.
        foreach($rows as $row) {
            $categorie = new Categorie($row['categorie_id'], $row['nom_categorie']);
            $categories[] = $categorie;
        }
    
        // Retourne le tableau d'objets Categorie.
        return $categories;
    }

    public function getCategorieById($id)
    {
        // Vérifie si l'ID est null, lance une exception si c'est le cas.
        if ($id == null) {
            throw new Exception('L\'id de la catégorie est obligatoire');
        }

        // Vérifie si l'ID est un entier, lance une exception si ce n'est pas le cas.
        if (!is_int($id)) {
            throw new Exception('L\'id de la catégorie doit être un nombre');
        }

        // Vérifie si l'ID est un nombre positif, lance une exception si ce n'est pas le cas.
        if ($id < 0) {
            throw new Exception('L\'id de la catégorie doit être un nombre positif');
        }

        // Requête SQL pour sélectionner la catégorie par ID.
        $query = "SELECT * FROM categories WHERE categorie_id = :id";

        // Prépare la requête.
        $stmt = $this->db->prepare($query);

        // Lie le paramètre ID à la requête.
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Exécute la requête.
        $stmt->execute();

        // Récupère la première ligne résultante en tant qu'associatif.
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retourne un objet Categorie à partir des résultats.
        return new Categorie($result['categorie_id'], $result['nom_categorie']);
    }
    
    

    // UPDATE
    public function updateCategorie(Categorie $categorie) {
        // Récupère l'ID et le nouveau nom de la catégorie depuis l'objet Categorie.
        $categorieId = $categorie->getCategorieId();
        $nouveauNom = $categorie->getNomCategorie();
    
        // Vérifie si l'ID ou le nouveau nom est vide, lance une exception si c'est le cas.
        if ($categorieId == null || $nouveauNom == null) {
            throw new Exception("champ vide");
        }
    
        // Vérifie si l'ID est un nombre positif, lance une exception si ce n'est pas le cas.
        if ($categorieId < 1) {
            throw new Exception("valeur negatif non autorisé");
        }
    
        // Requête SQL pour mettre à jour le nom de la catégorie.
        $query = "UPDATE categories SET nom_categorie = :nouveauNom WHERE categorie_id = :categorieId";
    
        // Prépare la requête.
        $stmt = $this->db->prepare($query);
    
        // Lie les paramètres à la requête.
        $stmt->bindParam(':categorieId', $categorieId, PDO::PARAM_INT);
        $stmt->bindParam(':nouveauNom', $nouveauNom, PDO::PARAM_STR);
    
        // Exécute la requête.
        $stmt->execute();
    
        // Récupère la catégorie mise à jour depuis la base de données.
        $updatedCategory = $this->getCategorieById($categorieId);
    
        // Retourne la catégorie mise à jour.
        return $updatedCategory;
    }
    

    // DELETE
    public function deleteCategorie($categorieId) {
        // Vérifie si l'ID est vide, lance une exception si c'est le cas.
        if ($categorieId == null) {
            throw new Exception('champs vide');
        }
    
        // Vérifie si l'ID est un nombre, lance une exception si ce n'est pas le cas.
        if (!is_int($categorieId)) {
            throw new Exception('id doit être un nombre');
        }
    
        // Vérifie si l'ID est un nombre positif, lance une exception si ce n'est pas le cas.
        if ($categorieId < 0) {
            throw new Exception('id doit être un nombre positif');
        }
    
        // Requête SQL pour supprimer la catégorie par ID.
        $query = "DELETE FROM categories WHERE categorie_id = :categorieId";
    
        // Prépare la requête.
        $stmt = $this->db->prepare($query);
    
        // Lie le paramètre ID à la requête.
        $stmt->bindParam(':categorieId', $categorieId, PDO::PARAM_INT);
    
        // Exécute la requête.
        $stmt->execute();
    
        // La fonction retourne null après la suppression réussie de la catégorie.
        return null;
    }


}

?>