<?php

require_once __DIR__ . '/../../src/ingredient/IngredientDAO.php';

use PHPUnit\Framework\TestCase;

class ingredientTest extends TestCase{
    // Propriété pour stocker l'instance PDO et l'instance du DAO pour les catégories
    private $pdo;
    private $ingredientDAO;

    protected function setUp(): void
    {
        // Crée une nouvelle instance de la base de données SQLite en mémoire.
        $this->pdo = new PDO('sqlite::memory:');

        // Exécute une requête SQL pour créer la table "ingredients".
        $this->pdo->exec('CREATE TABLE `ingredients` (
            `ingredient_id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `nom_ingredient` varchar(255) DEFAULT NULL,
            `unite_mesure` varchar(255) DEFAULT NULL
        )');

        // Initialise l'objet IngredientDAO avec la base de données créée.
        $this->ingredientDAO = new IngredientDAO($this->pdo);
    }

    /**
     * @dataProvider ajouterIngredientProvider
     */

    public function testAjouterIngredient($ingredient, $expected)
    {
        // Tableau des unités de mesure valides.
        $unites_mesures = ['g', 'kg', 'ml', 'L', 'c. à thé', 'c. à soupe', 'tasse', 'tasse à thé', 'tasse à café'];

        // Vérifie si le nom de l'ingrédient ou l'unité de mesure est vide, lance une exception si c'est le cas.
        if ($ingredient->getNomIngredient() == null || $ingredient->getUniteMesure() == null) {
            $this->expectException(Exception::class);
        }

        // Vérifie si le nom de l'ingrédient et l'unité de mesure sont des chaînes de caractères, lance une exception si ce n'est pas le cas.
        if (!is_string($ingredient->getNomIngredient()) || !is_string($ingredient->getUniteMesure())) {
            $this->expectException(Exception::class);
        }

        // Vérifie si l'unité de mesure est valide, lance une exception si ce n'est pas le cas.
        if (!in_array($ingredient->getUniteMesure(), $unites_mesures)) {
            $this->expectException(Exception::class);
        }

        $this->ingredientDAO->getIngredients();
        // Vérifie si l'ingrédient avec le même nom existe déjà, lance une exception si c'est le cas.
        foreach ($this->ingredientDAO->getIngredients() as $existingIngredient) {
            if ($existingIngredient->getNomIngredient() == $ingredient->getNomIngredient()) {
                $this->expectException(Exception::class);
            }
        }

        // Appelle la méthode addIngredient pour ajouter l'ingrédient et compare le résultat avec la valeur attendue.
        $this->assertEquals($expected, $this->ingredientDAO->addIngredient($ingredient));
    }

    /**
     * @dataProvider getAllIngredientsProvider
     */
    public function testGetIngredients($ingredients, $expected)
    {
        // Ajoute chaque ingrédient à la base de données.
        foreach ($ingredients as $ingredient) {
            $this->ingredientDAO->addIngredient($ingredient);
        }

        // Compte le nombre d'ingrédients dans la base de données et compare avec la valeur attendue.
        $this->assertEquals($expected, count($this->ingredientDAO->getIngredients()));
    }

    /**
     * @dataProvider getIngredientByIdProvider
     */

     public function testGetIngredientById($ingredient, $expected)
     {
        // Ajoute l'ingrédient à la base de données.
        $this->ingredientDAO->addIngredient($ingredient);
    
        // ID de l'ingrédient à récupérer.
        $id = 1;
    
        // Vérifie si l'ID est null, lance une exception si c'est le cas.
        if ($id == null) {
            $this->expectException(Exception::class);
        }
    
        // Vérifie si l'ID est un entier, lance une exception si ce n'est pas le cas.
        if (!is_int($id)) {
            $this->expectException(Exception::class);
        }
    
        // Vérifie si l'ID est un nombre positif, lance une exception si ce n'est pas le cas.
        if ($id < 0) {
            $this->expectException(Exception::class);
        }
    
        // Appelle la méthode getIngredientById pour récupérer l'ingrédient par ID et compare le résultat avec la valeur attendue.
        $this->assertEquals($expected, $this->ingredientDAO->getIngredientsById($id));
     }

    /**
     * @dataProvider updateIngredientProvider
     */
    public function testUpdateIngredient($ingredient1, $ingredient2, $expected)
    {
        // Ajouter plusieurs ingrédients dans la base de données
        $this->ingredientDAO->addIngredient($ingredient1);
        $this->ingredientDAO->addIngredient($ingredient2);

        $unites_mesures = ['g', 'kg', 'ml', 'L', 'c. à thé', 'c. à soupe', 'tasse', 'tasse à thé', 'tasse à café'];

        $id = 1; // ID de l'ingrédient à modifier

        $ingredient1->setNomIngredient('Pomme');
        $ingredient1->setUniteMesure('kg');

        $nom_ingredient = $ingredient1->getNomIngredient();

        if ($id == null || !is_int($id) || $id < 0) {
            $this->expectException(Exception::class);
        }

        // Get the existing ingredients before updating
        $existingIngredients = $this->ingredientDAO->getIngredients();

        // Expect an exception if the updated ingredient already exists
        foreach ($existingIngredients as $existingIngredient) {
            if ($existingIngredient->getNomIngredient() == $nom_ingredient) {
                $this->expectException(Exception::class);
            }
        }

        // Attempt to update the ingredient
        $this->ingredientDAO->updateIngredient($ingredient1);
    }

    /**
     * @dataProvider deleteIngredientProvider
     */
    public function testDeleteIngredient($ingredient, $expected)
    {
        // Ajoute l'ingrédient à la base de données.
        $this->ingredientDAO->addIngredient($ingredient);
    
        // ID de l'ingrédient à supprimer.
        $id = 1;
    
        // Vérifie si l'ID est null, n'est pas un entier, ou est un nombre négatif, lance une exception si c'est le cas.
        if ($id == null || !is_int($id) || $id < 0) {
            $this->expectException(Exception::class);
        }
    
        // Appelle la méthode deleteIngredient pour supprimer l'ingrédient et compare le résultat avec la valeur attendue.
        $this->assertEquals($expected, $this->ingredientDAO->deleteIngredient($id));
    }

    /**
     * @dataProvider getIdByNomIngredientProvider
     */

     public function testGetIdByNomIngredient($ingredient, $nom_ingredient, $expected)
     {
        // Vérifie si le nom de l'ingrédient est null, lance une exception si c'est le cas.
        if ($nom_ingredient == null) {
            $this->expectException(Exception::class);
        }
    
        // Vérifie si le nom de l'ingrédient est une chaîne de caractères, lance une exception si ce n'est pas le cas.
        if (!is_string($nom_ingredient)) {
            $this->expectException(Exception::class);
        }
    
        // Ajoute l'ingrédient à la base de données.
        $this->ingredientDAO->addIngredient($ingredient);
    
        // Appelle la méthode getIdByNomIngredient pour récupérer l'ID par le nom de l'ingrédient et compare le résultat avec la valeurattendue.
        $this->assertEquals($expected, $this->ingredientDAO->getIdByNomIngredient($nom_ingredient));
     }


    /**
    * Fournit des jeux de données pour tester les methodes
    *
    * Chaque jeu de données est constitué de deux objets Categorie : un à ajouter à la base de données et l'autre avec les modifications attendues.
    */
    
    public static function ajouterIngredientProvider()
    {
       return [ 
            [new Ingredient(null,null,'kg'), 1],
            [new Ingredient(null,'Pomme', null), 1],
            [new Ingredient(null,'Pomme', 'kg'), 1],
       ];
    }

    public static function getAllIngredientsProvider()
    {
        return [
            [[new Ingredient(null,'Pomme', 'kg')], 1],
            [[new Ingredient(null,'Pomme', 'kg'), new Ingredient(null,'Poire', 'kg')], 2],
            [[new Ingredient(null,'Pomme', 'kg'), new Ingredient(null,'Poire', 'kg'), new Ingredient(null,'Fraise', 'kg')], 3],
        ];
    }

    public static function getIngredientByIdProvider()
    {
        return [
            [new Ingredient(null,'Pomme', 'kg'), new Ingredient(1,'Pomme', 'kg')],
            [new Ingredient(null,'Poire', 'kg'), new Ingredient(1,'Poire', 'kg')],
            [new Ingredient(null,'Fraise', 'kg'), new Ingredient(1,'Fraise', 'kg')],
        ];
    }

    public static function updateIngredientProvider()
    {
        return [
            [new Ingredient(null,'Pomme', 'kg'), new Ingredient(1,'Poire', 'kg'), 1],
            [new Ingredient(null,'Poire', 'kg'), new Ingredient(1,'Pomme', 'kg'), 1],
            [new Ingredient(null,'Fraise', 'kg'), new Ingredient(1,'Pomme', 'kg'), 1],
        ];
    }

    public static function deleteIngredientProvider()
    {
        return [
            [new Ingredient(null,'Pomme', 'kg'), null],
            [new Ingredient(null,'Poire', 'kg'), null],
            [new Ingredient(null,'Fraise', 'kg'), null],
        ];
    }

    public static function getIdByNomIngredientProvider()
    {
        return [
            [new Ingredient(null,'Pomme', 'kg'), "Pomme", 1],
            [new Ingredient(null,'Poire', 'kg'),"", false],
            [new Ingredient(null,'Fraise', 'kg'), 3, false],
        ];
    }
}



?>