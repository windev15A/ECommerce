<?php

require_once('../inc/init.inc.php');

$users = getAll($bdd, 'membre');



require_once('../inc/inc_back/header.inc.php');
require_once('../inc/inc_back/nav.inc.php');
?>

<h1 class="text-center my-5">Affichage des utilisateur</h1>

<h2>Total des  inscrits : <span class="bg-success px-2 rounded"><?= count($users) ?></span></h2>
<!-- Affichage des données  -->
<table class="table">
    <thead>
        <tr>
            <?php foreach ($users[0] as $key => $user) : ?>
                <?php if($key != 'password'): ?>
                <th scope="col"><?= $key ?></th>
                <?php endif; ?>
            <?php endforeach ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $key => $user) : ?>
            <tr>
                <?php foreach ($user as $key => $value) : ?>
                    <?php if($key != 'password'): ?>
                    <td><?= $value ?></td>
                    <?php endif; ?>
                <?php endforeach; ?>

            </tr>
        <?php endforeach; ?>

    </tbody>
</table>

<!-- Formulaire de saisie -->


<?php
require_once('../inc/inc_back/footer.inc.php');
