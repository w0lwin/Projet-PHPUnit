<?php

require_once __DIR__ . '/../../src/ingredient/IngredientDAO.php';

use PHPUnit\Framework\TestCase;

class ingredientTest extends TestCase{
    private $pdo;
    private $ingredientDAO;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec('CREATE TABLE `ingredients` (
            `ingredient_id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `nom_ingredient` varchar(255) DEFAULT NULL,
            `unite_mesure` varchar(255) DEFAULT NULL
          )');
        $this->ingredientDAO = new IngredientDAO($this->pdo);
    }

    /**
     * @dataProvider ajouterIngredientProvider
     */

    public function testAjouterIngredient($ingredient, $expected)
    {
        $unites_mesures = ['g', 'kg', 'ml', 'L', 'c. à thé', 'c. à soupe', 'tasse', 'tasse à thé', 'tasse à café'];

        if ($ingredient->getNomIngredient() == null || $ingredient->getUniteMesure() == null) {
            $this->expectException(Exception::class);
        }

        if (!is_string($ingredient->getNomIngredient()) || !is_string($ingredient->getUniteMesure())) {
            $this->expectException(Exception::class);
        }

        if (!in_array($ingredient->getUniteMesure(), $unites_mesures)) {
            $this->expectException(Exception::class);
        }

        $this->ingredientDAO->getIngredients();
        foreach($this->ingredientDAO->getIngredients() as $existingIngredient){
            if($existingIngredient->getNomIngredient() == $ingredient->getNomIngredient()){
                $this->expectException(Exception::class);
            }
        }    

        $this->assertEquals($expected, $this->ingredientDAO->addIngredient($ingredient));
    }

    /**
     * @dataProvider getAllIngredientsProvider
     */
    public function testGetIngredients($ingredients, $expected)
    {
        foreach ($ingredients as $ingredient) {
            $this->ingredientDAO->addIngredient($ingredient);
        }
        //compter le nombre de ingredients dans la base de donnees
        $this->assertEquals($expected, count($this->ingredientDAO->getIngredients()));

    }

    /**
     * @dataProvider getIngredientByIdProvider
     */

    public function testGetIngredientById($ingredient, $expected)
    {
        $this->ingredientDAO->addIngredient($ingredient);

        $id = 1; //id de l'ingredient a recuperer

        if ($id == null) {
            $this->expectException(Exception::class);
        }

        if (!is_int($id)) {
            $this->expectException(Exception::class);
        }

        if ($id < 0) {
            $this->expectException(Exception::class);
        }

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
            [new Ingredient(null,'Pomme', 'kg'), new Ingredient(1,'Pomme', 'kg'), 1],
            [new Ingredient(null,'Poire', 'kg'), new Ingredient(1,'Poire', 'kg'), 1],
            [new Ingredient(null,'Fraise', 'kg'), new Ingredient(1,'Pomme', 'kg'), 1],
        ];
    }
}



?>