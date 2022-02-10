<?php

require_once('inc/init.inc.php');


// echo '<pre>';
// print_r($_POST);
// echo '</pre>';

// redirection vers la page profil s'il est connecter 

if(connect()){
    header('location:profil.php');
}


// test si l utilistaeur a cliquer sur deconnexion avec un parametre 'action' dans l URL

if(isset($_GET['action']) && $_GET['action'] == 'deconnexion'){
    unset($_SESSION['user']);
    
}


if (isset($_POST['pseudo_email'], $_POST['password'], $_POST['submit'])) {
    $verifUser = $bdd->prepare("SELECT * FROM membre WHERE pseudo = :pseudo OR email = :email");

    $verifUser->bindValue(":pseudo", $_POST['pseudo_email'], PDO::PARAM_STR);
    $verifUser->bindValue(":email", $_POST['pseudo_email'], PDO::PARAM_STR);

    $verifUser->execute();

    // echo "nb resultat    <b>" . $verifUser->rowCount().'</b><hr>';

    if ($verifUser->rowCount()) {

        $user = $verifUser->fetch(PDO::FETCH_ASSOC);
        // Controle de mot de passe 
        if (password_verify($_POST['password'], $user['password'])) {
            foreach ($user as $key => $value) {
                if ($key != 'password') {
                    $_SESSION['user'][$key] = $value;
                }
            }
            header("location: profil.php");
        } else {
            $error = "<div class='alert bg-danger role='alert col-6 mt-3 '>
            <p class='text-white text-center'>oooooooops Identifiants invalide</p>  
            </div>";
        }
    } else {
        $error = "<div class='alert bg-danger role='alert col-6 mt-3 '>
            <p class='text-white text-center'>oooooooops Identifiants invalide</p>  
        </div>";
    }


}




require_once('inc/inc_front/header.inc.php');
require_once('inc/inc_front/nav.inc.php');

?>

<?php if (isset($_SESSION['valide_inscription'])) echo $_SESSION['valide_inscription']; ?>
<?php if (isset($error)) echo $error; ?>
<h1 class="text-center my-5">Identifiez-vous</h1>


<form action="" method="post" class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4 mx-auto">
    <div class="mb-3">
        <label for="pseudo_email" class="form-label">Nom d'utilisateur / Email</label>
        <input type="text" class="form-control" id="pseudo_email" name="pseudo_email" placeholder="Saisir votre Email ou votre nom d'utilisateur">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Saisir votre mot de passe">
    </div>
    <div>
        <p class="text-end mb-0"><a href="inscription.php" class="alert-link text-dark">Pas encore de compte ? Cliquez ici</a></p>
        <p class="text-end m-0 p-0"><a href="" class="alert-link text-dark">Mot de passe oublié ?</a></p>
    </div>
    <input type="submit" name="submit" value="Continuer" class="btn btn-dark">
</form>

</main>

<?php

// On supprime dans la session l'indice 'valide_inscription' 
unset($_SESSION['valide_inscription']);
require_once('inc/inc_front/footer.inc.php');


?>