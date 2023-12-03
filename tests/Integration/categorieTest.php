<?php
require_once 'src/categories/CategorieDAO.php';

use PHPUnit\Framework\TestCase;

class CategorieTest extends TestCase{
    private $pdo;
    private $categorieDAO;

    protected function setUp(): void {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec('CREATE TABLE `categories` (
            `categorie_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `nom_categorie` varchar(255) DEFAULT NULL,
            PRIMARY KEY (`categorie_id`)
          )');
        $this->categorieDAO = new CategorieDAO($this->pdo);
    }

    
}




?>