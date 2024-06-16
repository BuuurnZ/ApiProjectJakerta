<?php

$action = $_GET["action"];
if(isset($_SESSION["autorisation"]) && $_SESSION["autorisation"] == "eleve"){
    switch($action){

        case "liste":
            $lesEleves = Utilisateur::getAll();
            include("Vue/Utilisateur/cListeAdh.php");
            break;

        
    }
} else {
    include("Vue/formAuth.php");
}
?>
