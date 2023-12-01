<?php

require_once 'recette/RecetteDAO.php';
require_once 'ingredient/IngredientDAO.php';
require_once 'categorie/CategorieDAO.php';


class GlobalVariable
{
    public static $ingredientDAO;
    public static $recetteDAO;
    public static $categorieDAO; 
    
    public static function init()
    {
        self::$ingredientDAO = new IngredientDAO(Connection::getConnection());
        self::$recetteDAO = new RecetteDAO(Connection::getConnection());
        self::$categorieDAO = new CategorieDAO(Connection::getConnection());
    }
}
?>
