<?php
require_once('init.inc.php');
// Function internaute Athentifie
function connect()
{
    if (!isset($_SESSION['user'])) {
        return false;
    } else {
        return true;
    }
}

// Si l utilisateur est un admin
function adminConnect()
{
    if (connect() && $_SESSION['user']['statut'] == 'admin') {
        return true;
    } else {
        return false;
    }
}


function getAll(PDO $bdd, string $table)
{
    $requete = $bdd->query("SELECT * FROM $table");

    $result = $requete->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function getDataById($arg)
{
    // $reqPanier = $bdd->prepare("SELECT * FROM panier WHERE id_membre = :id_membre AND status= :status");
    // $reqPanier->bindValue(':id_membre', $_SESSION['user']['id_membre'], PDO::PARAM_INT);
    // $reqPanier->bindValue(':status', false, PDO::PARAM_BOOL);
    // $reqPanier->execute();
    // $dataPanier = $reqPanier->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre style="margin-left:250px">';
    // print_r($arg[0]);
    // echo '</pre>';

  

    $requete = $arg['bdd']->query("SELECT * FROM $arg[table] WHERE $arg[table]");
    echo '<pre style="margin-left:250px">';
    print_r($requete->fetchAll());
    echo '</pre>';

}
