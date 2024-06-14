<?php

$action = $_GET["action"];
if(isset($_SESSION["autorisation"]) && $_SESSION["autorisation"] == "prof"){
    switch($action){

        case "accueil":
            $_GET["action"] = "listeP";
            include("Controller/SeanceController.php");
            break;

    }
}
else{
    include("Vue/formAuth.php");
}

?>