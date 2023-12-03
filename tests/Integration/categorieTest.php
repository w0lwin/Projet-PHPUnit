<?php

require_once __DIR__ . '/../../src/categorie/CategorieDAO.php';

use PHPUnit\Framework\TestCase;

class CategorieTest extends TestCase
{
    private $pdo;
    private $categorieDAO;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec('CREATE TABLE `categories` (
            `categorie_id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `nom_categorie` varchar(255) DEFAULT NULL
          )');
        $this->categorieDAO = new CategorieDAO($this->pdo);
    }

    /**
     * @dataProvider ajouterCategorieProvider
     */
    public function testAjouterCategorie($categorie, $expected)
    {
        if ($categorie->getNomCategorie() == null) {
            $this->expectException(Exception::class);
        }

        if (!is_string($categorie->getNomCategorie())) {
            $this->expectException(Exception::class);
        }

        $this->assertEquals($expected, $this->categorieDAO->ajouterCategorie($categorie));
    }

    /**
     * @dataProvider getAllCategoriesProvider
     */
    public function testGetAllCategories($categories)
    {
        foreach ($categories as $categorie) {
            $this->categorieDAO->ajouterCategorie($categorie);
        }
        //compter le nombre de categories dans la base de donnees
        $this->assertEquals(count($categories), count($this->categorieDAO->getAllCategories()));

    }

   /**
 * @dataProvider getCategorieByIdProvider
 */
    public function testGetCategorieById($categorie, $expected)
    {
        $this->categorieDAO->ajouterCategorie($categorie);

        $id = 1; //id de la categorie a recuperer

        if ($id == null) {
            $this->expectException(Exception::class);
        }

        if (!is_int($id)) {
            $this->expectException(Exception::class);
        }

        if ($id < 0) {
            $this->expectException(Exception::class);
        }

        $retrievedCategorie = $this->categorieDAO->getCategorieById($id);

        // Now use assertEquals with two arguments for comparison
        $this->assertEquals($expected, $retrievedCategorie);
    }

    /**
     * @dataProvider updateCategorieProvider
     */
    public function testUpdateCategorie($categorie, $expected)
    {
        $this->categorieDAO->ajouterCategorie($categorie);
        $nouveauNom= 'Fruits';

        $id = 1;

        if ($id == null || $nouveauNom == null) {
            $this->expectException(Exception::class);
        }

        if ($id < 0) {
            $this->expectException(Exception::class);
        }

        $retrievedCategorie = $this->categorieDAO->getCategorieById($id);
        $retrievedCategorie->setNomCategorie($nouveauNom);
        $this->categorieDAO->updateCategorie($retrievedCategorie);

        $this->assertEquals($expected, $this->categorieDAO->getCategorieById($id));
    }

    /**
     * @dataProvider deleteCategorieProvider
     */

    public function testDeleteCategorie($categorie, $expected)
    {
        $this->categorieDAO->ajouterCategorie($categorie);

        $id = 1;

        if ($id == null) {
            $this->expectException(Exception::class);
        }

        if ($id < 0) {
            $this->expectException(Exception::class);
        }

        if (!is_int($id)) {
            $this->expectException(Exception::class);
        }

        $categorie = $this->categorieDAO->deleteCategorie($id);

        $this->assertEquals($expected, $categorie);
    }



    public static function ajouterCategorieProvider()
    {
        return [
            [new Categorie(null, ''), true],
            [new Categorie(null, 'Legumes'), true],
            [new Categorie(null, 3), true],
        ];
    }

    public static function getAllCategoriesProvider()
    {
        return [
            [[new Categorie(null, 'Legumes')]],
            [[new Categorie(null, 'Legumes'), new Categorie(null, 'Fruits')]],
            [[new Categorie(null, 'Legumes'), new Categorie(null, 'Fruits'), new Categorie(null, 'Viandes')]],
        ];
    }
    
    public static function getCategorieByIdProvider()
    {
        return [
            [new Categorie(null, 'Legumes'), new Categorie(1, 'Legumes')],
            [new Categorie(null, 'Fruits'), new Categorie(1, 'Fruits')],
            [new Categorie(null, 'Viandes'), new Categorie(1, 'Viandes')],
        ];
    }
    
    public static function updateCategorieProvider()
    {
        return [
            [new Categorie(null, 'Legumes'), new Categorie(1, 'Fruits')],
            [new Categorie(null, 'Fruits'), new Categorie(1, 'Fruits')],
            [new Categorie(null, 'Viandes'), new Categorie(1, 'Fruits')],
        ];
    }

    public static function deleteCategorieProvider()
    {
        return [
            [new Categorie(null, 'Legumes'), null],
            [new Categorie(null, 'Fruits'), null],
            [new Categorie(null, 'Viandes'), null],
        ];
    }
    

}

