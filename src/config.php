<?php

// Connexion à la base de données avec PDO
try {
    $hote = "91.134.89.49";
    $utilisateur = "appCuisine";
    $motDePasse = "betre9-haqRax-zotmag";
    $nomDeLaBase = "appCuisine";
    $port = "8090";

    // Création d'une instance de PDO pour la connexion à la BDD
    $bdd = new PDO("mysql:host=$hote;dbname=$nomDeLaBase;port=$port", $utilisateur, $motDePasse);

    // Configuration de PDO pour générer des exceptions en cas d'erreur
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // En cas d'erreur de connexion, affiche un message d'erreur et arrête le script
    echo "Erreur de connexion à la base de données: " . $e->getMessage();
    die();
}
?>
