<?php
require_once('../inc/init.inc.php');

if (!adminConnect()) {
    header('location: ' . URL . 'connexion.php');
}

// echo '<pre style="margin-left:250px">'; print_r($_POST); echo'</pre>';
// echo '<pre style="margin-left:250px">'; print_r($_FILES); echo'</pre>';

// Fonction pour avoire les données 
$products = getAll($bdd, 'produit');

// echo '<pre style="margin-left:300px">';
// print_r('fffffffffffffffffffffffff'.count($products));
// echo '</pre>';

if (isset($_GET['id'], $_GET['action']) && $_GET['action'] == 'delete') {

    $delete = $bdd->prepare("DELETE FROM produit WHERE id_produit = :id_produit");
    $delete->bindValue(':id_produit', $_GET['id'], PDO::PARAM_INT);
    $delete->execute();

    $_GET['action'] = 'affichage';

    $deleteMessage = "<p class='col-7 mx-auto p-3 mt-3 bg-success text-white text-center '>l'article n° <strong> $_GET[id]</strong> a été supprimer avec succès</p>";
    // header('location: ?action=affichage');
}






if (isset($_POST['reference'], $_POST['categorie'], $_POST['titre'], $_POST['description'], $_POST['couleur'], $_POST['taille'], $_POST['public'], $_POST['prix'], $_POST['stock'])) {

    //Traitement / enregistrement de la photo produit

    // if(isset($_GET['action']) && $_GET['action'] == 'edit'){
    //     $photoBdd  = $photo;
    // }
    $photoBdd = '';
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
    }else{
        $photoBdd= $_POST['photoActuelle'];
    }
    if (isset($_GET['action']) && $_GET['action'] == 'ajout') {
        $insertProduct = $bdd->prepare("INSERT INTO produit (reference,categorie,titre,description,couleur,taille,public, prix,stock, photo) VALUES (:reference,:categorie,:titre,:description,:couleur,:taille,:public, :prix,:stock, :photo)");
    } else {
        
        $insertProduct = $bdd->prepare("UPDATE produit
           SET reference = :reference, categorie = :categorie, titre = :titre,description = :description,couleur = :couleur,taille = :taille,public = :public,prix = :prix,stock = :stock,photo = :photo
         WHERE id_produit = :id_produit");

        $insertProduct->bindValue(':id_produit', $_GET['id'], PDO::PARAM_INT);
    }


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
        $action = $_GET['action'] == 'ajout' ? 'ajouter' : 'modifier';
        $insertMessage = "<p class='col-7 mx-auto p-3 mt-3 bg-success text-white text-center '><strong> $_POST[reference]</strong>  a été $action avec succès</p>";
        $_GET['action'] = 'affichage';
    }

}




// Modification article

if (isset($_GET['id'], $_GET['action']) && $_GET['action'] == 'edit') {

    $req = $bdd->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $req->bindValue(':id_produit', $_GET['id'], PDO::PARAM_STR);
    $req->execute();
    $product = $req->fetch(PDO::FETCH_ASSOC);

    //     $id = $product['id_produit'];
    $reference = $product['reference'];
    $categorie = $product['categorie'];
    $titre = $product['titre'];
    $description = $product['description'];
    $couleur = $product['couleur'];
    $taille = $product['taille'];
    $public = $product['public'];
    $prix  = $product['prix'];
    $stock = $product['stock'];
    $photo = $product['photo'];


    //     header("location: ?action=edit&id=$id");
    //     echo '<pre style="margin-left:250px">';
    // print_r($reference);
    // echo '</pre>';

}

require_once('../inc/inc_back/header.inc.php');
require_once('../inc/inc_back/nav.inc.php');

?>
<!-- Affichage des données 
OK 1.requete de selection
OK 2. Recuperer le nombre de produits selectionées
OK 3.recuperer les informations sous forme de tableau
OK 4.déclarer le tableau HTML
OK 5.Afficher les entete du tableau
OK 6.Afficher tous les produits de la bdd
OK 7.Un lien pour Editer et supperimer 
-->
<div class="mt-3 text-center">
    <a href="?action=ajout" class="btn btn-secondary">Nouvelle article</a>
    <a href="?action=affichage" class="btn btn-secondary">Affichage des articles</a>
</div>

<?php if (isset($_GET['action']) && $_GET['action'] == 'affichage') : ?>
    <h1 class="text-center my-5">Affichage des articles</h1>

    <?php if (isset($deleteMessage)) echo $deleteMessage ?>
    <?php if (isset($insertMessage )) echo $insertMessage  ?>
    

    <h5 class="mt-3"><span class="bg-success text-white p-2 rounded "><?= count($products) ?></span>article(s) enregistrés</h5>

    <table class="table">
        <thead class="thead-dark">
            <tr>
                <?php foreach ($products[0] as $key => $value) : ?>
                    <th scope="col" class="text-center"><?= ucfirst($key) ?></th>
                <?php endforeach; ?>
                <th scope="col">Edite</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $key => $product) : ?>
                <tr>
                    <?php foreach ($product as $key => $value) : ?>
                        <?php if ($key == 'photo') : ?>
                            <td class="text-center"><img src="<?= $value ?>" alt="<?= $product['titre'] ?>" width="150px"></td>
                        <?php elseif ($key == 'description') : ?>
                            <td><?= substr($value, 0, 50) ?>...</td>
                        <?php elseif ($key == 'prix') : ?>
                            <td><strong><?= $value ?>€ </strong></td>
                        <?php elseif ($key == 'couleur') : ?>
                            <td style="background-color:<?= $value ?>;" class="text-white"> <?= $value ?></td>
                        <?php else : ?>
                            <td><?= $value ?></td>
                        <?php endif; ?>

                    <?php endforeach; ?>
                    <td><a href="?action=edit&id=<?= $product['id_produit'] ?>" ><i style="font-size: 2rem;" class="bi bi-pencil-square text-primary icon_edit"></i></a></td>
                    <td><a href="?action=delete&id=<?= $product['id_produit'] ?>" onclick="return confirm('Are you sure?')"><i style="font-size: 2rem;" class="bi bi-trash text-danger icon_edit"></i></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php

endif;

if (isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'edit')) :

?>


    <h1 class="text-center my-5"><?= $_GET['action'] == 'ajout' ?  'Ajout article' : 'Modification article' ?></h1>

    <form method="POST" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-6">
            <label for="reference" class="form-label">Référence</label>
            <input type="text" name="reference" class="form-control" id="reference" value="<?php if (isset($reference)) echo $reference ?>">
        </div>
        <div class="col-md-6">
            <label for="categorie" class="form-label">Catégorie</label>
            <input type="text" name="categorie" class="form-control" id="categorie" value="<?php if (isset($categorie)) echo $categorie ?>">
        </div>
        <div class="col-md-12">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" name="titre" class="form-control" id="titre" value="<?php if (isset($titre)) echo $titre ?>">
        </div>
        <div class="col-md-12">
            <label for="description" class="form-label">Description</label>
            <textarea type="text" name="description" class="form-control" rows="10" id="description"><?php if (isset($description)) echo $description ?></textarea>
        </div>

        <?php if (isset($photo)) : ?>
            <div class="col-md-12 d-inline text-center">
                <input type="file" name="photo" id="input" style="display:none;">
                <label for="input">
                    <small class="fst-italic">Photo actuelle de l'article. Vous pouvez uploader une nouvelle photo si vous souhaiter la modifier<br></small>
                    <img src="<?= $photo ?>" id="image" width="150px">
                </label>
                <input type="hidden" name="photoActuelle" value="<?php if (isset($photo)) echo $photo ?>">

            </div>
        <?php else : ?>
            <div class="col-md-4">
                <label for="photo" class="form-label">Photo</label>
                <input type="file" name="photo" class="form-control" id="photo">
            </div>
        <?php endif; ?>
        <div class="col-md-4">
            <label for="couleur" class="form-label">Couleur</label>
            <input type="color" name="couleur" class="form-control input-couleur" id="couleur" value="<?php if (isset($couleur)) echo $couleur ?>">
        </div>

        <div class="col-md-4">
            <label for="taille" class="form-label">State</label>
            <select id="taille" class="form-select" name="taille">
                <option value="s" <?php if (isset($taille) && $taille == "s") echo 'selected' ?>>S</option>
                <option value="m" <?php if (isset($taille) && $taille == "m") echo 'selected' ?>>M</option>
                <option value="l" <?php if (isset($taille) && $taille == "l") echo 'selected' ?>>L</option>
                <option value="xl" <?php if (isset($taille) && $taille == "xl") echo 'selected' ?>>XL</option>
                <option value="xxl" <?php if (isset($taille) && $taille == "xxl") echo 'selected' ?>>XXL</option>
            </select>
        </div>

        <div class="col-md-4">
            <label for="public" class="form-label">Public</label>
            <select id="public" class="form-select" name="public">
                <option value="homme" <?php if (isset($public) && $public == "homme") echo 'selected' ?>>Homme</option>
                <option value="femme" <?php if (isset($public) && $public == "femme") echo 'selected' ?>>Femme</option>
                <option value="mixte" <?php if (isset($public) && $public == "mixte") echo 'selected' ?>>Mixte</option>

            </select>
        </div>
        <div class="col-md-4">
            <label for="prix" class="form-label">Prix</label>
            <input type="number" name="prix" class="form-control" id="prix" value="<?php if (isset($prix)) echo $prix ?>">
        </div>
        <div class="col-md-4">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" id="stock" value="<?php if (isset($stock)) echo $stock ?>">
        </div>


        <div class="col-12">
            <button type="submit" class="btn btn-primary"><?= $_GET['action'] == 'ajout' ?  'Ajouter produit' : 'Modifier produit' ?></button>
        </div>
    </form>
<?php endif; ?>

<?php
require_once('../inc/inc_back/footer.inc.php');
