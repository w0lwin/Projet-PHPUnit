<?php

require_once __DIR__ . '/../../src/categorie/CategorieDAO.php';

use PHPUnit\Framework\TestCase;

class CategorieTest extends TestCase
{
    // Propriété pour stocker l'instance PDO et l'instance du DAO pour les catégories
    private $pdo;
    private $categorieDAO;

    // Méthode de configuration exécutée avant chaque test
    protected function setUp(): void
    {
        // Création d'une instance PDO en mémoire pour les tests
        $this->pdo = new PDO('sqlite::memory:');

        // Création de la table 'categories' avec deux colonnes : 'categorie_id' et 'nom_categorie'
        $this->pdo->exec('CREATE TABLE `categories` (
            `categorie_id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `nom_categorie` varchar(255) DEFAULT NULL
        )');

        // Instanciation du CategorieDAO avec l'instance PDO créée
        $this->categorieDAO = new CategorieDAO($this->pdo);
    }


    /**
     * @dataProvider ajouterCategorieProvider
     */
    public function testAjouterCategorie($categorie, $expected)
    {
        // vérification les entrées qui renvoie une erreur
        if ($categorie->getNomCategorie() == null) {
            $this->expectException(Exception::class);
        }

        if (!is_string($categorie->getNomCategorie())) {
            $this->expectException(Exception::class);
        }

        // Appelle la méthode ajouterCategorie et compare le résultat avec la valeur attendue.
        $this->assertEquals($expected, $this->categorieDAO->ajouterCategorie($categorie));
    }

    /**
     * @dataProvider getAllCategoriesProvider
     */
    public function testGetAllCategories($categories)
    {
        // Ajoute chaque catégorie à la base de données via la méthode ajouterCategorie.
        foreach ($categories as $categorie) {
            $this->categorieDAO->ajouterCategorie($categorie);
        }

        // Récupère le nombre de catégories dans la base de données via la méthode getAllCategories.
        $nombreCategoriesDansBaseDeDonnees = count($this->categorieDAO->getAllCategories());

        // Compare le nombre de catégories dans la base de données avec le nombre attendu.
        $this->assertEquals(count($categories), $nombreCategoriesDansBaseDeDonnees);
    }

   /**
 * @dataProvider getCategorieByIdProvider
 */
    public function testGetCategorieById($categorie, $expected)
    {
        // Ajoute la catégorie à la base de données via la méthode ajouterCategorie.
        $this->categorieDAO->ajouterCategorie($categorie);

        // ID de la catégorie à récupérer
        $id = 1;

        // vérification les entrées qui renvoie une erreur 
        if ($id == null) {
            $this->expectException(Exception::class);
        }

        if (!is_int($id)) {
            $this->expectException(Exception::class);
        }

        if ($id < 0) {
            $this->expectException(Exception::class);
        }

        // Appelle la méthode getCategorieById pour récupérer la catégorie par ID.
        $retrievedCategorie = $this->categorieDAO->getCategorieById($id);

        // Utilise assertEquals pour comparer la catégorie récupérée avec la valeur attendue.
        $this->assertEquals($expected, $retrievedCategorie);
    }

    /**
     * @dataProvider updateCategorieProvider
     */
    public function testUpdateCategorie($categorie, $expected)
    {
        // Ajoute la catégorie à la base de données via la méthode ajouterCategorie.
        $this->categorieDAO->ajouterCategorie($categorie);

        // Nouveau nom pour la mise à jour
        $nouveauNom = 'Fruits';

        // ID de la catégorie à mettre à jour
        $id = 1;

        // vérification les entrées qui renvoie une erreur
        if ($id == null || $nouveauNom == null) {
            $this->expectException(Exception::class);
        }

        if ($id < 0) {
            $this->expectException(Exception::class);
        }

        // Récupère la catégorie par ID.
        $retrievedCategorie = $this->categorieDAO->getCategorieById($id);

        // Met à jour le nom de la catégorie.
        $retrievedCategorie->setNomCategorie($nouveauNom);

        // Appelle la méthode updateCategorie pour mettre à jour la catégorie.
        $this->categorieDAO->updateCategorie($retrievedCategorie);

        // Vérifie si la catégorie mise à jour correspond à la valeur attendue.
        $this->assertEquals($expected, $this->categorieDAO->getCategorieById($id));
    }

    /**
     * @dataProvider deleteCategorieProvider
     */

    public function testDeleteCategorie($categorie, $expected)
    {
        // Ajoute la catégorie à la base de données via la méthode ajouterCategorie.
        $this->categorieDAO->ajouterCategorie($categorie);

        // ID de la catégorie à supprimer
        $id = 1;

        // vérification les entrées qui renvoie une erreur
        if ($id == null) {
            $this->expectException(Exception::class);
        }

        if ($id < 0) {
            $this->expectException(Exception::class);
        }

        if (!is_int($id)) {
            $this->expectException(Exception::class);
        }

        // Appelle la méthode deleteCategorie pour supprimer la catégorie par ID.
        $categorieSupprimee = $this->categorieDAO->deleteCategorie($id);

        // Compare la valeur attendue avec la catégorie supprimée.
        $this->assertEquals($expected, $categorieSupprimee);
    }


    /**
    * Fournit des jeux de données pour tester les methodes
    *
    * Chaque jeu de données est constitué de deux objets Categorie : un à ajouter à la base de données et l'autre avec les modifications attendues.
    */

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

