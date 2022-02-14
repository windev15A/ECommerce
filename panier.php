<?php


require_once('inc/init.inc.php');

echo '<pre style="margin-left:250px">'; 
print_r($_SESSION['panier']);
echo '</pre>';
if(!empty($_SESSION['panier'])) $dataPanier = $_SESSION['panier'];

$total = 0;


if (isset($_GET['action'], $_GET['id']) && $_GET['action'] == 'delete') {

    $people = $_SESSION['panier'];
    $found_key = array_search($_GET['id'], array_column($people, 'id_panier'));
    
    unset($people[$found_key]);

    unset($_SESSION['panier']);
    foreach($people as $value){

        array_push($_SESSION['panier'], $value);
    }
    
    header('location: panier.php');
    $deleteMessage = "<p class='col-7 mx-auto p-3 mt-3 bg-success text-white text-center '>l'article n° <strong> $_GET[id]</strong> a été supprimer avec succès</p>";
 
}






require_once('inc/inc_front/header.inc.php');
require_once('inc/inc_front/nav.inc.php');
?>
<h1 class="text-center my-5">Votre panier</h1>
<?php if (isset($deleteMessage)) echo $deleteMessage ?>
<?php if (empty($dataPanier)) : ?>
    <div class="container col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 mx-auto d-flex justify-content-center shadow-sm px-0 mb-2">

        <div class="col bg-white d-flex flex-column justify-content-center p-2 text-center">
            <p class="fst-italic">Votre panier est tristement <b> vide </b> </p>
            <i class="bi bi-emoji-frown-fill" style="font-size: 5rem; color: red;"></i>
            <h4><a href="boutique.php" class="alert-link text-dark titre-produit-panier">Retour à la boutique</a></h4>
        </div>

    </div>
<?php else : ?>

    <?php foreach ($dataPanier as $key => $value) :
        $produit = unserialize($value['produit']);

        $total += $produit['prix'] * $value['qty'];

    ?>
        <div class="container col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 mx-auto d-flex justify-content-center shadow-sm px-0 mb-2">
            <div class="col-md-2 bg-white p-2">
                <a href="fiche_produit.html"><img src="<?= $produit['photo'] ?>" alt="produit 1" class="img-panier"></a>
            </div>
            <div class="col-md-6 bg-white d-flex flex-column justify-content-center p-2">
                <h4><a href="fiche_produit.html" class="alert-link text-dark titre-produit-panier"><?= $produit['titre'] ?></a></h4>
                <p class="fw-bold fst-italic">Prix unitaire : <?= number_format($produit['prix'], 2) ?> €</p>
                <p>Quantité : <?= $value['qty'] ?></p>

                <p class="mb-0"><a href="?action=delete&id=<?= $value['id_panier'] ?>" onclick="return confirm('Are you sure?')" class="alert-link text-dark liens-supp-produit-panier">Supprimer</a></p>

            </div>
            <div class="col-md-4 bg-white d-flex justify-content-end align-items-center p-2">
                <p class="fw-bold mb-0"><?= number_format($produit['prix'] * $value['qty'], 2) ?> €</p>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<div class="container col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 d-flex justify-content-end align-items-center shadow-sm px-0 py-3 bg-white mt-2 mb-3">
    <h5 class="m-0 px-2 fw-bold">Sous total (<?= count($_SESSION['panier']) ?? 0 ?> articles) : <?= count($_SESSION['panier']) > 0 ?  number_format($total, 2) : 0 ?> €</h5>
</div>
<div class="container col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 p-0 text-end mb-5">
    <a href="paiment.php" class="btn btn-dark">FINALISER LA COMMANDE</a>
</div>





<?php

require_once('inc/inc_front/footer.inc.php');

?>