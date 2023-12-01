<?php

require_once('src/ingredient/IngredientDAO.php');

use PHPUnit\Framework\TestCase;

class IngredientTest extends TestCase {
    private $pdo;
    private $ingredientDAO;

    protected function setUp(): void {
        $this->configDatabase();
        $this->ingredientDAO = new IngredientDAO($this->pdo);
       
    }

    private function configDatabase():void{
        $this->pdo = new PDO(
            sprintf(
                'mysql:host=%s;port=%s;dbname=%s',
                getenv('DB_HOST'),
                getenv('DB_PORT'),
                getenv('DB_DATABASE')
            ),
            getenv('DB_USERNAME'),
            getenv('DB_PASSWORD')
        );
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function testAddIngredient(){

        $nom = 'Fraise';
        $unite_mesure = 'kg';
        $unites_mesures = ['g', 'kg', 'ml', 'L', 'c. à thé', 'c. à soupe', 'tasse', 'tasse à thé', 'tasse à café'];

        if($nom == null || $unite_mesure == null){
            $this->expectException(Exception::class);
        }

        if(!in_array($unite_mesure, $unites_mesures)){
            $this->expectException(Exception::class);
        }

        foreach($this->ingredientDAO->getIngredients() as $ingredient){
            if($ingredient->getNomIngredient() == $nom){
                $this->expectException(Exception::class);
            }
        }

        $ingredient = new Ingredient(null, $nom, $unite_mesure);
        $this->ingredientDAO->addIngredient($ingredient);
        
        $addedIngredient = $this->ingredientDAO->getIngredientsById(3);
    
        $this->assertEquals(3, $addedIngredient->getIngredientId());
        $this->assertEquals($ingredient->getNomIngredient(), $addedIngredient->getNomIngredient());
        $this->assertEquals($ingredient->getUniteMesure(), $addedIngredient->getUniteMesure());
    }

    public function testGetIngredients(){
        $ingredients = $this->ingredientDAO->getIngredients();
        $this->assertEquals(4, count($ingredients));
    }

    public function testGetIngredientsById(){
        $id=2;

        if($id == null){
            $this->expectException(Exception::class);
        }

        if($id < 0){
            $this->expectException(Exception::class);
        }

        if (!is_int($id)){
            $this->expectException(Exception::class);
        }
        $ingredient = $this->ingredientDAO->getIngredientsById($id);
        $this->assertEquals(2, $ingredient->getIngredientId());
        $this->assertEquals('Poire', $ingredient->getNomIngredient());
        $this->assertEquals('kg', $ingredient->getUniteMesure());
    }
    
    public function testUpdateIngredient(){
        $ingredient_id = 2;

        if($ingredient_id == null){
            $this->expectException(Exception::class);
        }


        $ingredient = $this->ingredientDAO->getIngredientsById($ingredient_id);
        $nom_ingredient = 'carrot';
        $unite_mesure = 'g';
        
        $unite_mesures = ['g', 'kg', 'ml', 'L', 'c. à thé', 'c. à soupe', 'tasse', 'tasse à thé', 'tasse à café'];
        
        
        if(!is_int($ingredient_id)){
            $this->expectException(Exception::class);
        }
        
        if($ingredient_id < 0){
            $this->expectException(Exception::class);
        }
        
        if(!in_array($unite_mesure, $unite_mesures)){
            $this->expectException(Exception::class);
        }
        
        foreach($this->ingredientDAO->getIngredients() as $ingredient){
            if($ingredient->getNomIngredient() == $nom_ingredient){
                $this->expectException(Exception::class);
            }
        }
        
        if(!is_string($nom_ingredient)){
            $this->expectException(Exception::class);
        }
        
        $ingredient->setNomIngredient($nom_ingredient);
        $ingredient->setUniteMesure($unite_mesure);
        $this->ingredientDAO->updateIngredient($ingredient);
        $updatedIngredient = $this->ingredientDAO->getIngredientsById($ingredient_id);
        $this->assertEquals('carrot', $updatedIngredient->getNomIngredient());
        $this->assertEquals('g', $updatedIngredient->getUniteMesure());
    }

    public function testDeleteIngredient(){

        $id = 3;

        if($id == null){
            $this->expectException(Exception::class);
        }

        if($id < 0){
            $this->expectException(Exception::class);
        }

        $this->ingredientDAO->deleteIngredient(3);
        $ingredients = $this->ingredientDAO->getIngredients();
        $this->assertEquals(3, count($ingredients));
    }


}
?>