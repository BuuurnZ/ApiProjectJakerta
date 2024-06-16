<?php

$action = $_GET["action"];
if(isset($_SESSION["autorisation"]) && $_SESSION["autorisation"] == "professeur"){
    switch($action){

        case "accueil":
            $_GET["action"] = "listeP";
            include("Vue/SeanceController.php");
            break;

    }
}
else{
    include("Vue/formAuth.php");
}

?>