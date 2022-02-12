<?php

require_once('inc/init.inc.php');

// 
$reqPanier = $bdd->prepare("SELECT * FROM panier WHERE id_membre = :id_membre AND status= :status");
$reqPanier->bindValue(':id_membre', $_SESSION['user']['id_membre'], PDO::PARAM_INT );
$reqPanier->bindValue(':status', false, PDO::PARAM_BOOL );
$reqPanier->execute();
$dataPanier = $reqPanier->fetchAll(PDO::FETCH_ASSOC);

    // echo '<pre style="margin-left:250px">';
    // print_r(unserialize($dataPanier[0]['produit'])['id_produit']);
    // // print_r($dataPanier);
    // echo '</pre>';



require_once('inc/inc_front/header.inc.php');
require_once('inc/inc_front/nav.inc.php');

?>

            <h1 class="text-center my-5">Votre panier</h1>

            <?php foreach($dataPanier as $key=>$value): ?>
            <div class="container col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 mx-auto d-flex justify-content-center shadow-sm px-0 mb-2">
                <div class="col-md-2 bg-white p-2">
                    <a href="fiche_produit.html"><img src="<?= unserialize($value['produit'])['photo'] ?>" alt="produit 1" class="img-panier"></a>
                </div>
                <div class="col-md-6 bg-white d-flex flex-column justify-content-center p-2">
                    <h4><a href="fiche_produit.html" class="alert-link text-dark titre-produit-panier"><?= unserialize($value['produit'])['titre'] ?></a></h4>
                    <p class="text-success fw-bold fst-italic">En stock !</p>
                    <p>Quantité : <?= $value['qty'] ?></p>
                    <p class="mb-0"><a href="" class="alert-link text-dark liens-supp-produit-panier">Supprimer</a></p>
                </div>
                <div class="col-md-4 bg-white d-flex justify-content-end align-items-center p-2">
                    <p class="fw-bold mb-0"><?= number_format(unserialize($value['produit'])['prix'], 2) ?> €</p>
                </div>
            </div>
            <?php endforeach; ?>
            
            <div class="container col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 d-flex justify-content-end align-items-center shadow-sm px-0 py-3 bg-white mt-2 mb-3">
                <h5 class="m-0 px-2 fw-bold">Sous total (<?= $reqPanier->rowCount() ?> articles) : 170.99€</h5>
            </div>
            <div class="container col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 p-0 text-end mb-5">
                <a href="" class="btn btn-dark">FINALISER LA COMMANDE</a>
            </div>

            <?php

require_once('inc/inc_front/footer.inc.php');

?>