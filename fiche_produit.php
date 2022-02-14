<?php

require_once('inc/init.inc.php');
if(!isset($_SESSION['panier'])) $_SESSION['panier'] = [];
if (isset($_GET['id']) && !empty($_GET['id'])) {

    $reqProduit = $bdd->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $reqProduit->bindValue(':id_produit', $_GET['id'], PDO::PARAM_INT);
    $reqProduit->execute();

    if ($reqProduit->rowCount()) {
        $produit = $reqProduit->fetch(PDO::FETCH_ASSOC);
    } else {
        header('location: boutique.php');
    }

    // echo '<pre style="margin-left:250px">';
    // print_r($produit);
    // echo '</pre>';

} else {
    header('location: boutique.php');
}



if (isset($_POST['qty']) && $_POST['qty'] > 0) {

    
        array_push($_SESSION['panier'], array(
            'id_panier' => count($_SESSION['panier'])+1,
            'produit' => serialize($produit),
            'qty' => $_POST['qty']
        ));

        $_SESSION['msgPanier'] = 'Produit Ajouter a votre panier';
        header('location: boutique.php');
} else {
    $qtyMessage = "<p class='col-7 mx-auto p-3 mt-3 bg-danger text-white text-center '>Selectionner une valeur quantité</p>";
}


require_once('inc/inc_front/header.inc.php');
require_once('inc/inc_front/nav.inc.php');

?>

<h1 class="text-center my-5">Détails de l'article</h1>
<?php if (isset($qtyMessage) && isset($_POST['qty'])) echo $qtyMessage ?>
<div class="row mb-5">
    <div class="bg-white shadow-sm rounded d-flex zone-card-fiche-produit">

        <a href="assets/img/tee-shirt1.jpg" data-lightbox="tee-shirt1" data-title="tee-shirt1" data-alt="tee-shirt1" class=""><img src="<?= $produit['photo'] ?>" class="img-produit-fiche" alt="<?= $produit['titre'] ?>"></a>

        <div class="col-12 col-sm-12 col-md-12 col-lg-9 card-body d-flex flex-column justify-content-center zone-card-body">
            <h5 class="card-title text-center fw-bold my-3"><?= $produit['titre'] ?></h5>
            <p class="card-text"><?= $produit['description'] ?></p>
            <p class="card-text fw-bold">Taille : <?= $produit['taille'] ?></p>
            <div class="d-flex align-items-center">
                <p class="card-text fw-bold">Couleur :</p>
                <div style="background-color: <?= $produit['couleur'] ?>; padding:3px; width: 100px; height: 38px"></div>
            </div>
            <p class="card-text fw-bold"><?= number_format($produit['prix'], 2) ?> €</p>



            <?php if ($produit['stock'] < 10 && $produit['stock'] != 0) : ?>
                <p class="card-text fw-bold fst-italic"><i class="bi bi-exclamation-triangle-fill text-warning"></i> Attention ! il ne reste que <?= $produit['stock'] ?> exemplaire(s) en stock</p>
            <?php elseif ($produit['stock'] > 10) : ?>
                <span class="p-2 text-success"> <i class="bi bi-check-square-fill"></i> En stock</span>
            <?php endif; ?>

            <?php if ($produit['stock'] > 0) : ?>
                <p class="card-text">
                <form method="POST" class="row g-3">
                    <div class="col-12 col-sm-7 col-md-4 col-lg-3 col-xl-3">
                        <label class="visually-hidden" for="autoSizingSelect">Quantité</label>
                        <select class="form-select <?php if (isset($qtyMessage)) echo 'border border-danger' ?>" id="autoSizingSelect" name="qty">
                            <option selected>Choisir une quantité...</option>
                            <?php for ($i = 1; $i <= $produit['stock'] && $i <= 30; $i++) : ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-sm">
                        <input type="submit" class="btn btn-dark" value="Ajouter au panier">
                    </div>
                </form>
            <?php else : ?>
                <p class="card-text fw-bold fst-italic text-danger"><i class="bi bi-slash-circle-fill text-danger"></i> Repteur de stock </p>

            <?php endif; ?>

            </p>
        </div>
    </div>
    <div class="d-flex justify-content-between">

        <p class="mt-1"><a href="boutique.php?cat=<?= $produit['categorie'] ?>" class="text-dark alert-link"><i class="bi bi-arrow-left-circle-fill"></i> Retour à la catégorie <?= $produit['categorie'] ?></a></p>
        <p class="mt-1"><a href="boutique.php" class="text-dark alert-link"> Retour à la boutique <i class="bi bi-arrow-right-circle-fill"></i></a></p>
    </div>
</div>
<?php

require_once('inc/inc_front/footer.inc.php');

?>