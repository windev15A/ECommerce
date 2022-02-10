<?php
require_once('inc/init.inc.php');


if(connect()){
    header('location:profil.php');
}

/*
    
    Exercice : 
    OK 1. Contrôler en PHP que l'on receptionne bien toute les données saisie dans le formulaire (print_r)
    OK 2. Faites en sorte d'informer l'internaute si le champ 'pseudo' est laissé vide 
    OK 3. Faites en sorte d'informer l'internaute si le pseudo n'est pas disponible (SELECT + ROWCOUNT)
    OK 4. Faites en sorte d'informer l'internaute si le champ 'email' est laissé vide 
    OK 5. Faites en sorte d'informer l'internaute si le champ 'email' n'est pas du bon format (filter_var)
    OK 6. Faites en sorte d'informer l'internaute si le email est déjà existant en BDD (SELECT + ROWCOUNT)
    OK 7. Faites en sorte d'informer l'internaute si le champ 'password' ou 'confirm_password' sont laissé vide
    ok 8. Faites en sorte d'informer l'internaute si les mot de passe ne correspondent pas 
    OK 9. Si l'internaute a correctement remplit le formulaire, réaliser le traitement PHP + SQL  permettant d'insérer un nouvel utilisateur dans la BDD à la validation du formulaire (PREPARE + BINDVALUE + INSERT + EXECUTE)
*/
// echo '<pre>';/
// print_r($_POST);
// echo '</pre>';

if (isset($_POST['civilite'], $_POST['pseudo'], $_POST['password'], $_POST['confirm_password'], $_POST['email'], $_POST['nom'], $_POST['prenom'], $_POST['adresse'], $_POST['ville'], $_POST['code_postal'])) {


    $border = 'border border-danger';
    $pseudoExist = $bdd->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $pseudoExist->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
    $pseudoExist->execute();

    // Erreur pseudo
    if (empty($_POST['pseudo'])) {
        $errorPseudo = "<small class='fst-italic text-danger'>Merci de saisir un nom d'utilisateur.</small>";
        $error = true;
    } elseif ($pseudoExist->rowcount() > 0) {
        $errorPseudo = "<small class='fst-italic text-danger'>Pseudo déja utilisé </small>";
        $error = true;
    }
    // echo '<pre>'; print_r($pseudoExist->rowcount()); echo'</pre>';

    // Erreur email
    $emailExist = $bdd->prepare("SELECT * FROM membre WHERE email = :email");
    $emailExist->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $emailExist->execute();
    if (empty($_POST['email'])) {
        $errorEmail = "<small class='fst-italic text-danger'>Merci de saisir une adresse Email.</small>";
        $error = true;
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errorEmail = "<small class='fst-italic text-danger'>Merci de saisir un Email valide (ex: test@test.com).</small>";
        $error = true;
    } elseif ($emailExist->rowcount() > 0) {
        $errorEmail = "<small class='fst-italic text-danger'>Compte existant à cette adresse Email </small>";
        $error = true;
    }

    // Erreur mot de passe

    if (empty($_POST['password']) || empty($_POST['confirm_password'])) {
        $errorPassword = "<small class='fst-italic text-danger'>Merci de renseigner les mots de passe  </small>";
        $error = true;
    } elseif ($_POST['password'] != $_POST['confirm_password']) {
        $errorPassword = "<small class='fst-italic text-danger'>Le mot de passe est incorrect </small>";
        $error = true;
    }

    if(!isset($_POST['pdc']))
    {
        $errorPdc = "<p class='col-7 bg-danger text-white text-center p-3 mx-auto'>Vous devez accepter les politiques de confidentialités</p>";
        $error = true;
    }


    if (!isset($error)) {

        $insertMember = $bdd->prepare("INSERT INTO membre (pseudo, password, nom, prenom, email, civilite, ville, code_postal, adresse) VALUES (:pseudo, :password, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse)");

        // Creation d'une clé de hachage du mot de passe 
       // On ne conserve jamais le mot de passe en 'clair' dans la BDD, pour cela nous devons créer une clé de hachage
        // password_hash() : focntion prédéfinie permettant de créer une clé de hachage du mot de passe en BDD
        // arguments : 
        // 1. Le mot de passe à haché
        // 2. Le type de cryptage 

        $_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $insertMember->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
        $insertMember->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
        $insertMember->bindValue(':password', $_POST['password'], PDO::PARAM_STR);
        $insertMember->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $insertMember->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
        $insertMember->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
        $insertMember->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
        $insertMember->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
        $insertMember->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);
        // $insertMember->bindValue(':statut', 'user', PDO::PARAM_STR);

        if ($insertMember->execute()) {
            $_SESSION['valide_inscription'] = "<div class='alert bg-success role='alert col-6 mt-3'>
            Félicitation ! Vous êtes maintenant inscrit sur le site. Vous pouvez dès à présent vous connecter 
            </div>";
            header("location:connexion.php");
        }
    }
}


require_once('inc/inc_front/header.inc.php');
require_once('inc/inc_front/nav.inc.php');

?>

<h1 class="text-center my-5">Créer votre compte</h1>
<?php if (isset($errorPdc)) echo $errorPdc; ?>
<form class="row g-3 mb-5" method="POST">
    <div class="col-6">
        <label for="civilite" class="form-label">Civilité</label>
        <select class="form-select" name="civilite" aria-label="Default select example">
            <option value="homme">Monsieur</option>
            <option value="femme">Madame</option>
        </select>
    </div>
    <div class="col-md-6">
        <label for="pseudo" class="form-label">Nom d'utilisateur</label>
        <input type="text" class="form-control <?php if (isset($errorPseudo))  echo $border; ?>" id="pseudo" name="pseudo" value="<?php if(isset($_POST['pseudo'])) echo $_POST['pseudo']; ?>">
        <?php if (isset($errorPseudo)) echo $errorPseudo; ?>
    </div>
    <div class="col-md-6">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" class="form-control <?php if (isset($errorPassword))  echo $border; ?>" id="password" name="password" >
    </div>
    <div class="col-md-6">
        <label for="confirm_password" class="form-label">Confirmer votre mot de passe</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
        <?php if (isset($errorPassword)) echo $errorPassword; ?>
    </div>
    <div class="col-12">
        <label for="email" class="form-label">Email</label>
        <input type="text" class="form-control <?php if (isset($errorEmail))  echo $border; ?>" id="email" name="email" placeholder="Saisir votre adresse email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
        <?php if (isset($errorEmail)) echo $errorEmail; ?>
    </div>
    <div class="col-6">
        <label for="prenom" class="form-label">Prénom</label>
        <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Saisir votre prénom" value="<?php if(isset($_POST['prenom'])) echo $_POST['prenom']; ?>">
    </div>
    <div class="col-md-6">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" class="form-control" id="nom" name="nom" placeholder="Saisir votre nom" value="<?php if(isset($_POST['nom'])) echo $_POST['nom']; ?>">
    </div>
    <div class="col-md-6">
        <label for="adresse" class="form-label">Adresse</label>
        <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Saisir votre adresse" value="<?php if(isset($_POST['adresse'])) echo $_POST['adresse']; ?>">
    </div>
    <div class="col-md-4">
        <label for="ville" class="form-label">Ville</label>
        <input type="text" class="form-control" id="ville" name="ville" placeholder="Saisir votre ville" value="<?php if(isset($_POST['ville'])) echo $_POST['ville']; ?>">
    </div>
    <div class="col-md-2">
        <label for="code_postal" class="form-label">Code postal</label>
        <input type="text" class="form-control" id="code_postal" name="code_postal" value="<?php if(isset($_POST['code_postal'])) echo $_POST['code_postal']; ?>">
    </div>
    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="gridCheck" name="pdc" value="checked">
            <label class="form-check-label" for="gridCheck">
                Accepter les <a href="" class="alert-link text-dark">politiques de confidentialité</a>
            </label>
        </div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-dark">Continuer</button>
    </div>
</form>

</main>

<?php

require_once('inc/inc_front/footer.inc.php');


?>