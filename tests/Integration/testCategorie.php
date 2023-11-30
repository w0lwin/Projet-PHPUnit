<?php

use PHPUnit\Framework\TestCase;

require_once("src\categorie\CategorieDAO.php");

class testCategorie extends TestCase{
    private $pdo;
    private $categorie;

    protected function setUp(): void{
        $this->configureDatabase();
        $this->categorie = new CategorieDAO($this->pdo);
    }

    private function configureDatabase():void{
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


    // public function testAjouterCategorie() {
    //     $categorie = new Categorie(null, 'plats');
    //     $this->categorie->ajouterCategorie($categorie);

    //     if(is_int($categorie->getNomCategorie())){
    //         $this->expectException(Exception::class);
    //     }
    //     if($categorie->getNomCategorie() == null){
    //         $this->expectException(Exception::class);
    //     }
        
    //     $addedCategorie = $this->categorie->getCategorieById(2);
    //     var_dump($addedCategorie);
    
    //     $this->assertEquals(2, $addedCategorie->getCategorieId());
    //     $this->assertEquals($categorie->getNomCategorie(),$addedCategorie->getNomCategorie());

    // }

    // public function testGetCategorieById(){
    //     $categories = $this->categorie->getCategorieById(1);

    //     $this->assertInstanceOf(Categorie::class, $categories);
    //     $this->assertEquals(1, $categories->getCategorieId());
    // }
    
    // public function testGetCategorieAll(){
    //     $categories = $this->categorie->getAllCategories();
    //     var_dump($categories);
    //     $this->assertNotEmpty($categories);

    //     foreach ($categories as $categorie) {
    //         $this->assertInstanceOf(Categorie::class, $categorie);
    //     } 
    // }
    
    // public function testUpdateCategorie(){
    //     $id = 1;
    //     $categories = $this->categorie->getCategorieById($id);
    //     $this->assertInstanceOf(Categorie::class, $categories);
    //     if ($categories instanceof Categorie) {
    //         $new_categorie = "boisson";
    //         $categories->setNomCategorie($new_categorie);

    //         $update = $this->categorie->updateCategorie($categories);
    
    //         $this->assertTrue($update);
    
    //         $categorieMiseAJour = $this->categorie->getCategorieById($id);
    
    //         $this->assertEquals($new_categorie, $categorieMiseAJour->getNomCategorie());
    //     }
    // } 

    // public function testRemoveCategorie(){
    //     $id = 4;
    //     $categories = $this->categorie->getCategorieById($id);
    //     $this->assertInstanceOf(Categorie::class, $categories);
    
    //     if($categories instanceOf Categorie){
    //         $remove = $this->categorie->deleteCategorie($id); 

    //         $this->assertTrue($remove);
    //         $categorieDelete = $this->categorie->getCategorieById($id);
    
    //         $this->assertNull($categorieDelete);
    //     }
    // }
    
    

}





?>