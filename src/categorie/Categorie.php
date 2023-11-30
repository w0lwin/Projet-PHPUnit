<?php

class Categorie {
    // création variables
    protected $categorie_id;
    protected $nom_categorie;

    // fonction construct
    public function __construct($categorie_id, $nom_categorie){
        $this->categorie_id = $categorie_id;
        $this->nom_categorie = $nom_categorie;
    }

    // Getter
    public function getCategorieId(){
        return $this->categorie_id;
    }

    public function getNomCategorie(){
        return $this->nom_categorie;
    }

    // Setter
    public function setCategorieId($id){
        $this->categorie_id = $id;
    }

    public function setNomCategorie($nom){
        $this->nom_categorie = $nom;
    }

}

?>