<?php

require_once __DIR__ . '/../../src/recette/RecetteDAO.php';
require_once __DIR__ . '/../../src/ingredient/IngredientDAO.php';
require_once __DIR__ . '/../../src/categorie/CategorieDAO.php';

use PHPUnit\Framework\TestCase;

class RecetteTest extends TestCase
{
    private $pdo;
    private $recetteDAO;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec('CREATE TABLE `recettes` (
            `id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `nom_recette` varchar(255) NOT NULL,
            `instruction` text NOT NULL,
            `temps_preparation` int NOT NULL,
            `temps_cuisson` int NOT NULL,
            `difficulte` int NOT NULL,
            `categories_id` INTEGER NOT NULL
        )');
        
        $this->pdo->exec('CREATE TABLE `recette_ingredients` (
            `id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `recette_id` INTEGER NOT NULL,
            `categories_id` INTEGER NOT NULL,
            `quantite` int NOT NULL
        )');
        
        $this->pdo->exec('CREATE TABLE `categories` (
            `categorie_id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `nom_categorie` varchar(255) DEFAULT NULL
        )');

        $this->pdo->exec('CREATE TABLE `ingredients` (
            `ingredient_id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `nom_ingredient` varchar(255) DEFAULT NULL,
            `unite_mesure` varchar(50) DEFAULT NULL
        )');

          
        $this->recetteDAO = new RecetteDAO($this->pdo);
    }


    /**
     * @dataProvider addRecetteProvider
     */

     public function testAddRecette($recette, $expected)
     {
         if ($recette->getNomRecette() == null || $recette->getInstruction() == null || $recette->getTempsPreparation() == null || $recette->getTempsCuisson() == null || $recette->getDifficulte() == null || $recette->getCategorieId() == null || $recette->getIngredients() == null ) {
             $this->expectException(Exception::class);
         }

        if (!is_int($recette->getTempsPreparation()) || !is_int($recette->getTempsCuisson()) || !is_int($recette->getDifficulte()) || !is_int($recette->getCategorieId())) {
            $this->expectException(Exception::class);
        }

        if (!is_string($recette->getNomRecette()) || !is_string($recette->getInstruction())) {
            $this->expectException(Exception::class);

        }
         $this->assertEquals($expected, $this->recetteDAO->addRecette($recette));


     }

     /**
      * @dataProvider getRecettesProvider
      */

        public function testGetRecettes($recettes)
        {
            foreach ($recettes as $recette) {
                $this->recetteDAO->addRecette($recette);
            }
            //compter le nombre de recettes dans la base de donnees
            $this->assertEquals(count($recettes), count($this->recetteDAO->getRecettes()));
        }


        /**
     * @dataProvider getRecettesProvider
     */
    public function testGetRecetteById($recettes)
    {
        foreach ($recettes as $recette) {
            $this->recetteDAO->addRecette($recette);
        }

        // Utilisation de la première recette du tableau pour tester
        $recette = reset($recettes);

        // Utilisation de l'id de la recette pour le test
        $recetteId = $recette->getId();

        // Ajout d'une condition pour tester l'id de la recette
        if (!is_int($recetteId)) {
            $this->expectException(Exception::class);
        }

        if ($recetteId == null) {
            $this->expectException(Exception::class);
        }

        // Compter le nombre de recettes dans la base de données
        $this->assertEquals(1, count($this->recetteDAO->getRecetteById($recetteId)));
    }

    /**
     * @dataProvider getRecetteByTitleProvider
     */
    public function testGetRecetteByTitle($recettes)
    {
        foreach ($recettes as $recette) {
            $this->recetteDAO->addRecette($recette);
        }

        // Utilisation de la première recette du tableau pour tester
        $recette = reset($recettes);

        // Utilisation du titre de la recette pour le test
        $recetteTitle = $recette->getNomRecette();

        // Ajout d'une condition pour tester le titre de la recette
        if (!is_string($recetteTitle)) {
            $this->expectException(Exception::class);
        }

        if ($recetteTitle == null) {
            $this->expectException(Exception::class);
        }

        // Compter le nombre de recettes dans la base de données
        $this->assertEquals(1, count($this->recetteDAO->getRecetteByTitle($recetteTitle)));
    }

    /**
     * @dataProvider getIngredientsRecetteProvider
     */
    public function testGetIngredientsRecette($recettes)
    {
        foreach ($recettes as $recette) {
            $this->recetteDAO->addRecette($recette);
        }

        // Utilisation de la première recette du tableau pour tester
        $recette = reset($recettes);

        // Utilisation de l'id de la recette pour le test
        $recetteId = $recette->getId();

        // Ajout d'une condition pour tester l'id de la recette
        if (!is_int($recetteId)) {
            $this->expectException(Exception::class);
        }

        if ($recetteId == null) {
            $this->expectException(Exception::class);
        }

        // Compter le nombre de recettes dans la base de données
        $this->assertEquals(1, count($this->recetteDAO->getIngredientsRecette($recetteId)));
    }

    /**
     * @dataProvider updateRecetteProvider
     */

    public function testUpdateRecette($recette, $expected)
    {
        if ($recette->getNomRecette() == null || $recette->getInstruction() == null || $recette->getTempsPreparation() == null || $recette->getTempsCuisson() == null || $recette->getDifficulte() == null || $recette->getCategorieId() == null || $recette->getIngredients() == null ) {
            $this->expectException(Exception::class);
        }

       if (!is_int($recette->getTempsPreparation()) || !is_int($recette->getTempsCuisson()) || !is_int($recette->getDifficulte()) || !is_int($recette->getCategorieId())) {
           $this->expectException(Exception::class);
       }

       if (!is_string($recette->getNomRecette()) || !is_string($recette->getInstruction())) {
           $this->expectException(Exception::class);

       }

       $recette->setNomRecette("nom");
        $recette->setInstruction("i");
       $this->recetteDAO->updateRecette($recette);

        $this->assertEquals($expected, $this->recetteDAO->getRecetteById($recette->getId()));
    }

     public static function addRecetteProvider()
     {
        return [
            [new Recette(null, null, null, null, null, null,null,null), false],
            [new Recette(null, null, "instruction", 10, 10, 10, 1, 1), true],
            [new Recette(null, "nom_recette", null, 10, 10, 10, 1, 1), true],
            [new Recette(null, "nom_recette", "instruction", null, 10, 10, 1, 1), true],
            [new Recette(null, "nom_recette", "instruction", 10, null, 10, 1, 1), true],
            [new Recette(null, "nom_recette", "instruction", 10, 10, null, 1, 1), true],
            [new Recette(null, "nom_recette", "instruction", 10, 10, 10, null, 1), true],
            [new Recette(null, "nom_recette", "instruction", 10, 10, 10, 1, null), true],
            [new Recette(null,"nom_recette", "instruction", 10, 10, 10, 1, 1), true],
            [new Recette(null,"nom_recette", 1, 10, 10, 10, 1, 1, 1), false],
            [new Recette(null,"nom_recette", "instruction", "temps_preparation", 10, 10, 1, 1), false],
            [new Recette(null,1, "instruction", 10, "temps_cuisson", 10, 1, 1), false],
            [new Recette(null,"nom_recette", "instruction", 10, 10, "difficulte", 1, 1), false],
            [new Recette(null,"nom_recette", "instruction", 10, 10, 10, "categorie_id", 1), false],
            ];         
     }

        public static function getRecettesProvider()
        {
            return [
                [[new Recette(null, "nom_recette", "instruction", 10, 10, 10, 1, 1)]],
            ];
        }

        public static function getRecetteByTitleProvider()
        {
            return [
                [
                [new Recette(null, "nom_recette", "instruction", 10, 10, 10, 1, 1)],
                [new Recette(null, "michel", "instruction", 10, 10, 10, 1, 1)]
                ],
            ];
        }

        public static function getIngredientsRecetteProvider()
        {
            return [
                [
                [new Recette(null, "nom_recette", "instruction", 10, 10, 10, 1, 1)],
                [new Recette(null, "michel", "instruction", 10, 10, 10, 1, 1)]
                ],
            ];
        }

        public static function updateRecetteProvider()
        {
            return [
                [new Recette(null, "nom_recette", "instruction", 10, 10, 10, 1, 1), new Recette(1, "nom", "i", 10, 10, 10, 1, 1)],
            ];
        }
}
?>