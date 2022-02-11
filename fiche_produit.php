<?php

require_once('inc/init.inc.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    

    $reqProduit = $bdd->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $reqProduit->bindValue(':id_produit', $_GET['id'], PDO::PARAM_INT);
    $reqProduit->execute();

    if($reqProduit->rowCount()){
        $produit = $reqProduit->fetch(PDO::FETCH_ASSOC);
        
    }else 
    {
        header('location: boutique.php');
    }

    // echo '<pre style="margin-left:250px">';
    // print_r($produit);
    // echo '</pre>';

}else{
    header('location: boutique.php');
}


require_once('inc/inc_front/header.inc.php');
require_once('inc/inc_front/nav.inc.php');

?>

<h1 class="text-center my-5">Détails de l'article</h1>

<div class="row mb-5">
    <div class="bg-white shadow-sm rounded d-flex zone-card-fiche-produit">

        <a href="assets/img/tee-shirt1.jpg" data-lightbox="tee-shirt1" data-title="tee-shirt1" data-alt="tee-shirt1" class=""><img src="<?= $produit['photo'] ?>" class="img-produit-fiche" alt="<?= $produit['titre'] ?>"></a>

        <div class="col-12 col-sm-12 col-md-12 col-lg-9 card-body d-flex flex-column justify-content-center zone-card-body">
            <h5 class="card-title text-center fw-bold my-3"><?= $produit['titre'] ?></h5>
            <p class="card-text"><?= $produit['description'] ?></p>
            <p class="card-text fw-bold">Taille : <?= $produit['taille'] ?></p>
            <p class="card-text fw-bold" >Couleur :<span class="text-white" style="background-color: <?= $produit['couleur'] ?>; padding:3px;"><?= $produit['couleur'] ?></span> </p>
            <p class="card-text fw-bold"><?= number_format($produit['prix'],2) ?> €</p>
            <p class="card-text">
            
            <?php if($produit['stock'] <= 0): ?>
                <span class="bg-danger p-2 text-white"> Produit non desponible en stock</span>
            <?php elseif($produit['stock'] <= 10): ?>    
                <form action="panier.html" class="row g-3">
                    <div class="col-12 col-sm-7 col-md-4 col-lg-3 col-xl-3">
                        <label class="visually-hidden" for="autoSizingSelect">Quantité</label>
                        <select class="form-select" id="autoSizingSelect" name="quantity">
                            <option selected>Choisir une quantité...</option>
                            <?php for($i=1; $i<= $produit['stock']; $i++ ): ?>
                            <option value="<?=$i?>"><?=$i?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-sm">
                        <input type="submit" class="btn btn-dark" value="Ajouter au panier">
                    </div>
                </form>
              <?php else: ?>
                
                    
                <?php endif; ?>    
            </p>
        </div>
    </div>
    <p class="mt-1"><a href="boutique.php" class="text-dark alert-link"><i class="bi bi-arrow-left-circle-fill"></i> Retour à la boutique</a></p>
</div>
<?php

require_once('inc/inc_front/footer.inc.php');

?>