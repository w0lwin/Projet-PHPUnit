<?php
require_once('Ingredient.php');
// require_once '../config.php';


class IngredientDAO{
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function getIngredientsById($id){
        // Vérifie si l'ID est null, lance une exception si c'est le cas.
        if($id == null){
            throw new Exception('L\'id de l\'ingrédient est obligatoire');
        }
    
        // Vérifie si l'ID est un entier, lance une exception si ce n'est pas le cas.
        if(!is_int($id)){
            throw new Exception('L\'id de l\'ingrédient doit être un nombre');
        }
    
        // Vérifie si l'ID est un nombre positif, lance une exception si ce n'est pas le cas.
        if($id < 0){
            throw new Exception('L\'id de l\'ingrédient doit être un nombre positif');
        }
    
        // Requête SQL pour sélectionner l'ingrédient par ID.
        $sql = "SELECT * FROM ingredients WHERE ingredient_id = :id";
    
        // Prépare la requête.
        $stmt = $this->db->prepare($sql);
    
        // Lie le paramètre ID à la requête.
        $stmt->bindParam(':id', $id);
    
        // Exécute la requête.
        $stmt->execute();
    
        // Récupère le résultat de la requête sous forme de tableau associatif.
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Crée un nouvel objet Ingredient avec les données récupérées et le retourne.
        $ingredient = new Ingredient($result['ingredient_id'], $result['nom_ingredient'], $result['unite_mesure']);
        return $ingredient;
    }

    public function getIngredients(){
        // Requête SQL pour sélectionner tous les ingrédients.
        $sql = "SELECT * FROM ingredients";
    
        // Prépare la requête.
        $stmt = $this->db->prepare($sql);
    
        // Exécute la requête.
        $stmt->execute();
    
        // Récupère tous les résultats de la requête sous forme de tableau associatif.
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Initialise un tableau vide pour stocker les objets Ingredient.
        $ingredients = [];
    
        // Parcourt les résultats et crée un objet Ingredient pour chaque ligne, ajoutant chaque objet au tableau.
        foreach($result as $row){
            $ingredient = new Ingredient($row['ingredient_id'], $row['nom_ingredient'], $row['unite_mesure']);
            array_push($ingredients, $ingredient);
        }
    
        // Retourne le tableau d'objets Ingredient.
        return $ingredients;
    }

    public function addIngredient(Ingredient $ingredient){
        // Récupère le nom et l'unité de mesure de l'ingrédient.
        $nom_ingredient = $ingredient->getNomIngredient();
        $unite_mesure = $ingredient->getUniteMesure();

        // Tableau des unités de mesure valides.
        $unites_mesures = ['g', 'kg', 'ml', 'L', 'c. à thé', 'c. à soupe', 'tasse', 'tasse à thé', 'tasse à café'];

        // Vérifie si le nom de l'ingrédient ou l'unité de mesure est vide, lance une exception si c'est le cas.
        if($nom_ingredient == null || $unite_mesure == null){
            throw new Exception('Le nom de l\'ingrédient et l\'unité de mesure sont obligatoires');
        }

        // Vérifie si l'unité de mesure est valide, lance une exception si ce n'est pas le cas.
        if(!in_array($unite_mesure, $unites_mesures)){
            throw new Exception('L\'unité de mesure n\'est pas valide');
        }

        // Récupère tous les ingrédients existants.
        $this->getIngredients();
        // Vérifie si l'ingrédient avec le même nom existe déjà, lance une exception si c'est le cas.
        foreach($this->getIngredients() as $ingredient){
            if($ingredient->getNomIngredient() == $nom_ingredient){
                throw new Exception('L\'ingrédient existe déjà');
            }
        }

        // Vérifie si le nom de l'ingrédient et l'unité de mesure sont des chaînes de caractères, lance une exception si ce n'est pas le cas.
        if(!is_string($nom_ingredient) || !is_string($unite_mesure)){
            throw new Exception('Le nom de l\'ingrédient doit être une chaîne de caractères');
        }

        // Requête SQL pour insérer un nouvel ingrédient dans la base de données.
        $sql = "INSERT INTO ingredients (nom_ingredient, unite_mesure) VALUES (:nom_ingredient, :unite_mesure)";

        // Prépare la requête.
        $stmt = $this->db->prepare($sql);

        // Lie les paramètres de la requête aux valeurs de l'ingrédient.
        $stmt->bindParam(':nom_ingredient', $nom_ingredient);
        $stmt->bindParam(':unite_mesure', $unite_mesure);

        // Exécute la requête.
        $stmt->execute();

        // Récupère l'ID de l'ingrédient ajouté.
        $ingredient_id = $this->db->lastInsertId();

        // Retourne l'ID de l'ingrédient ajouté.
        return $ingredient_id;
    }

    public function updateIngredient(Ingredient $ingredient){
        // Récupère l'ID, le nom et l'unité de mesure de l'ingrédient.
        $ingredient_id = $ingredient->getIngredientId();
        $nom_ingredient = $ingredient->getNomIngredient();
        $unite_mesure = $ingredient->getUniteMesure();

        // Tableau des unités de mesure valides.
        $unites_mesures = ['g', 'kg', 'ml', 'L', 'c. à thé', 'c. à soupe', 'tasse', 'tasse à thé', 'tasse à café'];

        // Vérifie si l'ID de l'ingrédient, le nom et l'unité de mesure sont renseignés, lance une exception si ce n'est pas le cas.
        if($ingredient_id == null || $nom_ingredient == null || $unite_mesure == null){
            throw new Exception('L\'id de l\'ingrédient, le nom de l\'ingrédient et l\'unité de mesure sont obligatoires');
        }

        // Vérifie si l'ID de l'ingrédient est un nombre, lance une exception si ce n'est pas le cas.
        if(!is_int($ingredient_id)){
            throw new Exception('L\'id de l\'ingrédient doit être un nombre');
        }

        // Vérifie si l'ID de l'ingrédient est un nombre positif, lance une exception si ce n'est pas le cas.
        if($ingredient_id < 0){
            throw new Exception('L\'id de l\'ingrédient doit être un nombre positif');
        }

        // Vérifie si l'unité de mesure est valide, lance une exception si ce n'est pas le cas.
        if(!in_array($unite_mesure, $unites_mesures)){
            throw new Exception('L\'unité de mesure n\'est pas valide');
        }

        // Vérifie si l'ingrédient avec le même nom existe déjà, lance une exception si c'est le cas.
        foreach ($this->ingredientDAO->getIngredients() as $existingIngredient) {
            if ($existingIngredient->getNomIngredient() == $nom_ingredient) {
                throw new Exception('L\'ingrédient existe déjà');
            }
        }

        // Vérifie si le nom de l'ingrédient est une chaîne de caractères, lance une exception si ce n'est pas le cas.
        if(!is_string($nom_ingredient)){
            throw new Exception('Le nom de l\'ingrédient doit être une chaîne de caractères');
        }

        // Requête SQL pour mettre à jour un ingrédient dans la base de données.
        $sql = "UPDATE ingredients SET nom_ingredient = :nom_ingredient, unite_mesure = :unite_mesure WHERE ingredient_id = :ingredient_id";

        // Prépare la requête.
        $stmt = $this->db->prepare($sql);

        // Lie les paramètres de la requête aux valeurs de l'ingrédient.
        $stmt->bindParam(':ingredient_id', $ingredient_id);
        $stmt->bindParam(':nom_ingredient', $nom_ingredient);
        $stmt->bindParam(':unite_mesure', $unite_mesure);

        // Exécute la requête.
        $stmt->execute();
    }

    public function deleteIngredient($ingredient_id){
        // Vérifie si l'ID de l'ingrédient est renseigné, lance une exception si ce n'est pas le cas.
        if($ingredient_id == null){
            throw new Exception('L\'id de l\'ingrédient est obligatoire');
        }

        // Vérifie si l'ID de l'ingrédient est un nombre, lance une exception si ce n'est pas le cas.
        if(!is_int($ingredient_id)){
            throw new Exception('L\'id de l\'ingrédient doit être un nombre');
        }

        // Vérifie si l'ID de l'ingrédient est un nombre positif, lance une exception si ce n'est pas le cas.
        if($ingredient_id < 0){
            throw new Exception('L\'id de l\'ingrédient doit être un nombre positif');
        }

        // Requête SQL pour supprimer un ingrédient de la base de données.
        $sql = "DELETE FROM ingredients WHERE ingredient_id = :ingredient_id";

        // Prépare la requête.
        $stmt = $this->db->prepare($sql);

        // Lie le paramètre de la requête à la valeur de l'ID de l'ingrédient.
        $stmt->bindParam(':ingredient_id', $ingredient_id);

        // Exécute la requête.
        $stmt->execute();
    }
    

    

public function getIdByNomIngredient($nomIngredient)
{
    // Vérifie si le nom de l'ingrédient est renseigné, lance une exception si ce n'est pas le cas.
    if($nomIngredient == null){
        throw new Exception('Le nom de l\'ingrédient est obligatoire');
    }

    // Vérifie si le nom de l'ingrédient est une chaîne de caractères, lance une exception si ce n'est pas le cas.
    if(!is_string($nomIngredient)){
        throw new Exception('Le nom de l\'ingrédient doit être une chaîne de caractères');
    }
    
    // Requête SQL pour obtenir l'ID d'un ingrédient en recherchant par son nom.
    $stmt = $this->db->prepare("SELECT ingredient_id FROM ingredients WHERE nom_ingredient = :nom_ingredient");
    $stmt->bindParam(':nom_ingredient', $nomIngredient);
    $stmt->execute();

    // Fetch retourne un tableau associatif ou un booléen (false si aucune ligne n'est trouvée)
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifie si $result est un tableau et contient la clé 'ingredient_id'
    if ($result && array_key_exists('ingredient_id', $result)) {
        return $result['ingredient_id'];
    } else {
        // Retourne false si l'ingrédient n'est pas trouvé.
        return false;
    }
}
    
    


}


?>