<?php
class Recette{

    private $id;
    private $nom_recette;
    private $instruction;
    private $temps_preparation;
    private $temps_cuisson;
    private $difficulte;
    private $categorie_id;
    private $ingredients;

    public function __construct($id, $nom_recette, $instruction, $temps_preparation, $temps_cuisson, $difficulte, $categorie_id, $ingredients){
        $this->id = $id;
        $this->nom_recette = $nom_recette;
        $this->instruction = $instruction;
        $this->temps_preparation = $temps_preparation;
        $this->temps_cuisson = $temps_cuisson;
        $this->difficulte = $difficulte;
        $this->categorie_id = $categorie_id;
        $this->ingredients = $ingredients;
    }

    public function getId(){
        return $this->id;
    }

    public function getNomRecette(){
        return $this->nom_recette;
    }

    public function getInstruction(){
        return $this->instruction;
    }

    public function getTempsPreparation(){
        return $this->temps_preparation;
    }

    public function getTempsCuisson(){
        return $this->temps_cuisson;
    }

    public function getDifficulte(){
        return $this->difficulte;
    }

    public function getCategorieId(){
        return $this->categorie_id;
    }

    public function getIngredients(){
        return $this->ingredients;
    }

    public function setNomRecette($nom_recette){
        $this->nom_recette = $nom_recette;
    }

    public function setInstruction($instruction){
        $this->instruction = $instruction;
    }

    public function setTempsPreparation($temps_preparation){
        $this->temps_preparation = $temps_preparation;
    }

    public function setTempsCuisson($temps_cuisson){
        $this->temps_cuisson = $temps_cuisson;
    }

    public function setDifficulte($difficulte){
        $this->difficulte = $difficulte;
    }

    public function setCategorieId($categorie_id){
        $this->categorie_id = $categorie_id;
    }

    public function setIngredients($ingredients){
        $this->ingredients = $ingredients;
    }

}

?>