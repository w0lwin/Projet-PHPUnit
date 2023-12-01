<?php

class GlobalVariable
{
    public static $ingredientDAO;
    public static $recetteDAO;
    public static $categorieDAP;
    
    public static function init()
    {
        self::$ingredientDAO = new IngredientDAO(Connection::getConnection());
        self::$recetteDAO = new RecetteDAO(Connection::getConnection());
        self::$categorieDAO = new CategorieDAO(Connection::getConnection());
    }

}
?>