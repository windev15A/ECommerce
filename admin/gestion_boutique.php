<?php
require_once('../inc/init.inc.php');

// echo '<pre style="margin-left:250px">'; print_r($_POST); echo'</pre>';
// echo '<pre style="margin-left:250px">'; print_r($_FILES); echo'</pre>';

$req = $bdd->query("SELECT * FROM produit", PDO::FETCH_ASSOC);

$data = $req->fetchAll();

// echo '<pre style="margin-left:250px">';
// print_r($data[0]['photo']);
// echo '</pre>';







if (isset($_POST['reference'], $_POST['categorie'], $_POST['titre'], $_POST['description'], $_POST['couleur'], $_POST['taille'], $_POST['public'], $_POST['prix'], $_POST['stock'])) {

    //Traitement / enregistrement de la photo produit

    if (!empty($_FILES['photo']['name'])) {
        // Creation un nom de photo avec la concatination avec la reference 
        $nomPhoto = $_POST['reference'] . "-" . $_FILES['photo']['name'];
        // echo "<p style='margin-left:250px'> $nomPhoto </p>";

        // Url de l image 
        $photoBdd = URL . "assets/uploads/$nomPhoto";
        // echo "<p style='margin-left:250px'> $photoBdd </p>";

        // Definir le chemin du serveur
        $photoDossier = RACINE_SITE . "assets/uploads/$nomPhoto";
        // echo "<p style='margin-left:250px'> $photoDossier </p>";


        // Copie l'image dans le dossier ULPOADS
        copy($_FILES['photo']['tmp_name'], $photoDossier);

        $insertProduct = $bdd->prepare("INSERT INTO produit (reference,categorie,titre,description,couleur,taille,public, prix,stock, photo) VALUES (:reference,:categorie,:titre,:description,:couleur,:taille,:public, :prix,:stock, :photo)");

        $insertProduct->bindValue(':reference', $_POST['reference'], PDO::PARAM_STR);
        $insertProduct->bindValue(':categorie', $_POST['categorie'], PDO::PARAM_STR);
        $insertProduct->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
        $insertProduct->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
        $insertProduct->bindValue(':couleur', $_POST['couleur'], PDO::PARAM_STR);
        $insertProduct->bindValue(':taille', $_POST['taille'], PDO::PARAM_STR);
        $insertProduct->bindValue(':public', $_POST['public'], PDO::PARAM_STR);
        $insertProduct->bindValue(':prix', $_POST['prix'], PDO::PARAM_INT);
        $insertProduct->bindValue(':stock', $_POST['stock'], PDO::PARAM_INT);
        $insertProduct->bindValue(':photo', $photoBdd, PDO::PARAM_STR);
        // $insertProduct->execute();

        if ($insertProduct->execute()) {
            $insertMessage = "<p class='col-7 mx-auto p-3 mt-3 bg-success text-white text-center '><strong> $_POST[reference]</strong> a été enregistrer avec succès</p>";
        }
    }
}

require_once('../inc/inc_back/header.inc.php');
require_once('../inc/inc_back/nav.inc.php');

?>
<!-- Affichage des données 
1.requete de selection
2. Recuperer le nombre de produits selectionées
3.recuperer les informations sous forme de tableau
4.déclarer le tableau HTML
5.Afficher les entete du tableau
6.Afficher tous les produits de la bdd
7.Un lien pour Editer et supperimer 
-->

<h5 class="mt-3">Le nombre de produits dans la base : <span class="bg-success text-white p-2 rounded "><?= $req->rowCount() ?></span></h5>

<table class="table">
    <thead class="thead-dark">
        <tr>
            <?php foreach ($data[0] as $key => $value) : ?>
                <th scope="col"><?= $key ?></th>
            <?php endforeach; ?>
            <th scope="col">Edite</th>
            <th scope="col">Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $key => $value) : ?>
            <tr>
                <?php foreach ($value as $key => $value) : ?>
                    <?php if ($key == 'photo') : ?>
                        <td class="text-center"><img src="<?= $value ?>" alt="product" class="w-25 h-25"></td>
                    <?php elseif ($key == 'description') : ?>
                        <td><?= substr($value, 0, 100) ?></td>
                    <?php elseif ($key == 'couleur') : ?>
                        <td style="background-color:<?= $value ?>;"></td>
                    <?php else : ?>
                        <td><?= $value ?></td>
                    <?php endif; ?>

                <?php endforeach; ?>
                <td><a href="#"><i class="bi bi-pencil-square text-primary w-25 h-25"></i></a></td>
                <td><a href="#"><i class="bi bi-trash text-danger"></i></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>





<h1 class="text-center my-5">Ajout produit</h1>

<?php if (isset($insertMessage)) echo $insertMessage ?>

<form method="POST" enctype="multipart/form-data" class="row g-3">
    <div class="col-md-6">
        <label for="reference" class="form-label">Référence</label>
        <input type="text" name="reference" class="form-control" id="reference">
    </div>
    <div class="col-md-6">
        <label for="categorie" class="form-label">Catégorie</label>
        <input type="text" name="categorie" class="form-control" id="categorie">
    </div>
    <div class="col-md-12">
        <label for="titre" class="form-label">Titre</label>
        <input type="text" name="titre" class="form-control" id="titre">
    </div>
    <div class="col-md-12">
        <label for="description" class="form-label">Description</label>
        <textarea type="text" name="description" class="form-control" id="description"></textarea>
    </div>
    <div class="col-md-4">
        <label for="photo" class="form-label">Photo</label>
        <input type="file" name="photo" class="form-control" id="photo">
    </div>
    <div class="col-md-4">
        <label for="couleur" class="form-label">Couleur</label>
        <input type="color" name="couleur" class="form-control input-couleur" id="couleur">
    </div>

    <div class="col-md-4">
        <label for="taille" class="form-label">State</label>
        <select id="taille" class="form-select" name="taille">
            <option value="s">S</option>
            <option value="m">M</option>
            <option value="l">L</option>
            <option value="xl">XL</option>
            <option value="xll">XXL</option>
        </select>
    </div>

    <div class="col-md-4">
        <label for="public" class="form-label">Public</label>
        <select id="public" class="form-select" name="public">
            <option value="homme">Homme</option>
            <option value="femme">Femme</option>
            <option value="mixte">Mixte</option>

        </select>
    </div>
    <div class="col-md-4">
        <label for="prix" class="form-label">Prix</label>
        <input type="number" name="prix" class="form-control" id="prix">
    </div>
    <div class="col-md-4">
        <label for="stock" class="form-label">Stock</label>
        <input type="number" name="stock" class="form-control" id="stock">
    </div>


    <div class="col-12">
        <button type="submit" class="btn btn-primary">Valider</button>
    </div>
</form>


<?php
require_once('../inc/inc_back/footer.inc.php');
