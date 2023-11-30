<?php

class Ingredient{
    private $ingredient_id;
    private $nom_ingredient;
    private $unite_mesure;

    public function __construct($ingredient_id, $nom_ingredient, $unite_mesure){
        $this->ingredient_id = $ingredient_id;
        $this->nom_ingredient = $nom_ingredient;
        $this->unite_mesure = $unite_mesure;
    }

    public function getIngredientId(){
        return $this->ingredient_id;
    }

    public function getNomIngredient(){
        return $this->nom_ingredient;
    }

    public function getUniteMesure(){
        return $this->unite_mesure;
    }

    public function setNomIngredient($nom_ingredient){
        $this->nom_ingredient = $nom_ingredient;
    }

    public function setUniteMesure($unite_mesure){
        $this->unite_mesure = $unite_mesure;
    }


}



?>