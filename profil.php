<?php
require_once('inc/init.inc.php');

// echo '<pre>';
// print_r($_SESSION['user']);
// echo '</pre>';

if(!connect()){
    header('location:connexion.php');
}
require_once('inc/inc_front/header.inc.php');
require_once('inc/inc_front/nav.inc.php');
?>
<h1 class="text-center my-5">Vos informations personnelles</h1>

<!-- Exo : Afficher l'ensemble des données de l'utilisateur sur la page web en passant par le fichier session de l'utilisateur ($_SESSION) ne pas afficher l id sur la page web  -->


<div class="col-5 mx-auto card mb-5 shadow-sm">
    <div class="card-body">

        <?php

        foreach ($_SESSION['user'] as $key => $value) :

            if ($key != 'id_membre' && $key != 'statut') :
        ?>
                <p class="d-flex justify-content-between">

                    <strong><?= ucfirst($key) ?></strong>
                    <span><?= ucfirst($value) ?></span>
                </p>
        <?php
            endif;
        endforeach; ?>


    </div>
</div>

<?php
require_once('inc/inc_front/footer.inc.php');

?>