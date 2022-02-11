<?php

require_once('inc/init.inc.php');
setlocale(LC_MONETARY, 'fr_FR');
// Selection articles BDD


// Test si y a une categorie entrer dans l URL et qu il n'est pas vide 
if (isset($_GET['cat']) && !empty($_GET['cat'])) {
    $reqcat = $bdd->prepare("SELECT * FROM produit WHERE categorie = :categorie");
    $reqcat->bindValue(':categorie', $_GET['cat'], PDO::PARAM_STR);
    $reqcat->execute();
    if ($reqcat->rowCount()) {
        $dataprd = $reqcat->fetchAll(PDO::FETCH_ASSOC);
    }
    // Si la requet ne returne pas de resultat c'est que la cat dans l URL est modifier et que la categorie n'exist pas dans la BDD
    else {
        header('location: boutique.php');
    }

    // echo '<pre style="margin-left:250px">';
    // print_r($dataprd);
    // echo '</pre>';

} else
// si le parametere cat est vide oui y a pas On affiche tous les produits
{
    $reqcat = $bdd->query("SELECT * FROM produit");
    $dataprd = $reqcat->fetchAll(PDO::FETCH_ASSOC);
}

require_once('inc/inc_front/header.inc.php');
require_once('inc/inc_front/nav.inc.php');

?>
<h1 class="text-center my-5">Shopping</h1>

<p class="my-5">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Delectus, labore. Dolor voluptatem nobis ea deleniti, sit possimus eligendi iure recusandae rem eius. Doloribus delectus quas, tempore rem laboriosam nesciunt pariatur velit, illum sint, necessitatibus ea eaque provident. Cupiditate alias repellat aliquid veniam quibusdam corrupti, non odit asperiores illo eligendi necessitatibus! Fugiat quo in provident minus ullam praesentium natus amet sequi delectus quia incidunt beatae rem, labore quisquam pariatur accusantium exercitationem enim suscipit consequatur dolorum animi commodi saepe? Eos quas, aliquid blanditiis officia ipsum natus ea. Porro officiis qui totam unde dignissimos nesciunt repudiandae possimus numquam pariatur placeat! Magnam et aperiam hic officiis? Veniam, laborum voluptate nemo, qui tempore voluptates sed at, suscipit facere sint totam eos beatae nam aperiam molestiae! Asperiores non officia cupiditate itaque sapiente fuga earum illo quibusdam? Adipisci quia aliquid laboriosam saepe, dignissimos eos expedita molestiae quaerat nisi quae ratione provident, optio ad. Recusandae iure hic culpa!</p>

<div class="accordion col-12 col-sm-10 col-md-4 col-lg-3 col-xl-3 mx-auto my-5" id="accordionExample">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Catégories
            </button>
        </h2>

        <?php

        $catPdos = $bdd->query("SELECT DISTINCT(categorie) FROM produit");
        ?>

        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <?php while ($arrayCat = $catPdos->fetch(PDO::FETCH_ASSOC)) :
                ?>
                    <p><a href="?cat=<?= $arrayCat['categorie'] ?>" class="alert-link text-dark"><?= $arrayCat['categorie'] ?></a></p>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
    <?php foreach ($dataprd as $key => $produit) : ?>
        <div class="col">
            <div class="card shadow-sm rounded">
                <a href="fiche_produit.php?id=<?= $produit['id_produit'] ?>"><img src="<?= $produit['photo'] ?>" class="card-img-top" alt="<?= $produit['titre'] ?>"></a>
                <div class="card-body">
                    <h5 class="card-title text-center"><a href="fiche_produit.php?id=<?= $produit['id_produit'] ?>" class="alert-link text-dark titre-produit-boutique"><?= $produit['titre'] ?></a></h5>
                    <p class="card-text"><?= substr($produit['description'], 0, 200) ?>...</p>
                    <p class="card-text fw-bold"><?= number_format($produit['prix'], 2) ?> €</p>
                    <p class="card-text text-center"><a href="fiche_produit.php?id=<?= $produit['id_produit'] ?>" class="btn btn-outline-dark">En savoir plus</a></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>



<?php include_once('inc/inc_front/footer.inc.php') ?>