<?php
require_once 'src/recette/RecetteDAO.php';
require_once 'src/ingredient/IngredientDAO.php';

use PHPUnit\Framework\TestCase;

class RecetteTest extends TestCase
{

    private $pdo;
    private $recetteDAO;
    private $ingredientDAO;

    protected function setUp(): void
    {
        $this->configDatabase();
        $this->recetteDAO = new RecetteDAO($this->pdo);
        $this->ingredientDAO = new IngredientDAO($this->pdo);
    }

    private function configDatabase(): void
    {
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

    public function testAddRecette( )
    {
        $nomRecette = 'Sac a foutre';
        $instruction = 'Faire cuire le poulet dans une poele';
        $tempsPreparation = 10;
        $tempsCuisson = 20;
        $difficulte = 1;
        $categorie = 2;
        $ingredients = [2, 4];
        $quantite = [2, 4];
        
       
        if ($nomRecette == null || $instruction == null || $tempsPreparation == null || $tempsCuisson == null || $difficulte == null || $ingredients == null) {
            $this->expectException(Exception::class);
        }

        if (!is_int($tempsPreparation) || !is_int($tempsCuisson) || !is_int($difficulte)) {
            $this->expectException(Exception::class);
        }

        foreach ($this->recetteDAO->getRecettes() as $recette) {
            if ($recette->getNomRecette() == $nomRecette) {
                $this->expectException(Exception::class);
            }
        }

        $ingredientsRecette = [];
        foreach ($ingredients as $ingredient) {
            $ingredientsRecette[] = $this->ingredientDAO->getIngredientsById($ingredient);
        }

        var_dump($ingredientsRecette);
        $recette = new Recette(null, $nomRecette, $instruction, $tempsPreparation, $tempsCuisson, $difficulte,  $categorie, $ingredientsRecette);
        $this->recetteDAO->addRecette($recette, $quantite);

        $addedRecette = $this->recetteDAO->getRecetteById(3);

        $this->assertEquals(3, $addedRecette->getId());
        $this->assertEquals($recette->getNomRecette(), $addedRecette->getNomRecette());
        $this->assertEquals($recette->getInstruction(), $addedRecette->getInstruction());
        $this->assertEquals($recette->getTempsPreparation(), $addedRecette->getTempsPreparation());
        $this->assertEquals($recette->getTempsCuisson(), $addedRecette->getTempsCuisson());
        $this->assertEquals($recette->getDifficulte(), $addedRecette->getDifficulte());
    }





    public function testRecetteById()
    {
        $id = 1;

        if ($id == null) {
            $this->expectException(InvalidArgumentException::class);
        }

        if (!is_int($id)) {
            $this->expectException(InvalidArgumentException::class);
        }

        $recette = $this->recetteDAO->getRecetteById($id);

        $this->assertEquals(1, $recette->getId());
        $this->assertEquals('Poulet au curry', $recette->getNomRecette());
        $this->assertEquals('Faire cuire le poulet dans une poele', $recette->getInstruction());
        $this->assertEquals(10, $recette->getTempsPreparation());
        $this->assertEquals(20, $recette->getTempsCuisson());
        $this->assertEquals(1, $recette->getDifficulte());
    }

    public function testGetRecettes()
    {
        $recettes = $this->recetteDAO->getRecettes();

        $this->assertCount(13, $recettes);
    }

    public function getIngredientsRecette()
    {
        $id = 1;

        if ($id == null) {
            $this->expectException(InvalidArgumentException::class);
        }

        if (!is_int($id)) {
            $this->expectException(InvalidArgumentException::class);
        }

        $ingredients = $this->recetteDAO->getIngredientsRecette($id);

        $this->assertCount(3, $ingredients);
    }


    public function testUpdateRecette()
    {
        $id = 13;
        $nomRecette = 'Poulet au curry';
        $instruction = 'Faire cuire le poulet dans une poele';
        $tempsPreparation = 10;
        $tempsCuisson = 20;
        $difficulte = 1;
        $categorie = 2;
        $ingredients = [5, 2];
        $quantite = [2, 4];

        if ($id == null || $nomRecette == null || $instruction == null || $tempsPreparation == null || $tempsCuisson == null || $difficulte == null || $ingredients == null) {
            $this->expectException(Exception::class);
        }

        if (!is_int($id) || !is_int($tempsPreparation) || !is_int($tempsCuisson) || !is_int($difficulte)) {
            $this->expectException(Exception::class);
        }

        $ingredientsRecette = [];
        foreach ($ingredients as $ingredient) {
            $ingredientsRecette[] = $this->ingredientDAO->getIngredientsById($ingredient);
        }
        var_dump($ingredientsRecette);
        $recette = new Recette($id, $nomRecette, $instruction, $tempsPreparation, $tempsCuisson, $difficulte,  $categorie, $ingredientsRecette);
        $this->recetteDAO->updateRecette($recette, $quantite);

        $updatedRecette = $this->recetteDAO->getRecetteById($id);

        $this->assertEquals($id, $updatedRecette->getId());
        $this->assertEquals($recette->getNomRecette(), $updatedRecette->getNomRecette());
        $this->assertEquals($recette->getInstruction(), $updatedRecette->getInstruction());
        $this->assertEquals($recette->getTempsPreparation(), $updatedRecette->getTempsPreparation());
        $this->assertEquals($recette->getTempsCuisson(), $updatedRecette->getTempsCuisson());
        $this->assertEquals($recette->getDifficulte(), $updatedRecette->getDifficulte());
    }

    public function testDeleteRecette()
    {
        $id = 13;

        if ($id == null) {
            $this->expectException(InvalidArgumentException::class);
        }

        if (!is_int($id)) {
            $this->expectException(InvalidArgumentException::class);
        }

        $this->recetteDAO->deleteRecette($id);

        $recette = $this->recetteDAO->getRecetteById($id);

        $this->assertNull($recette);
    }
}

?>