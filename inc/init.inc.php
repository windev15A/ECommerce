<?php

//Connexion  a la BDD

$bdd = new PDO('mysql:host=localhost;dbname=ecommerce', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
]);

// SESSION

session_start();

// CHEMIN / CONSTANTE

define("RACINE_SITE", $_SERVER['DOCUMENT_ROOT'] . '/php/09-ecommerce/');

    // echo RACINE_SITE.'<hr>';
    // cette constente retourne le chemin physique di dossier 09-ecommerce sur le serveur 
    // contexte : lors de l'enregistrement d'image produit sur le serveur, nous aurons besoin de définir le chemin complet dans lequel doit être enregistrée la photo sur le serveur

define("URL", "http://localhost/php/09-ecommerce/");    
// Cette constante définit l'adresse http de notre site ecommerce sur le serveur
// Cette constante servira, entre autres, à enregistrer et à définir l'URL d'une image produit qui sera stockée en BDD 
// ex : http://localhost/PHP-wf3-1098/09-ecommerce/asset/img/tee-shirt1.jpg



// FAILLES XSS
foreach ($_POST as $key => $value) {
    $_POST[$key] = htmlentities($value);
}

foreach ($_GET as $key => $value) {
    $_GET[$key] = htmlentities($value);
}


// INCLUSIONS

require_once('fonction.inc.php');