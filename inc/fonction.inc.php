<?php

// Function internaute Athentifie
function connect()
{
    if (!isset($_SESSION['user'])) {
        return false;
    } else {
        return true;
    }
}

// Si l utilisateur est un admin
function adminConnect(){
    if(connect() && $_SESSION['user']['statut'] == 'admin'){
        return true;
    }else{
        return false;
    }
}
