<?php

$action = $_GET["action"];


switch($action){
    case "liste":
        if(isset($_SESSION["autorisation"]) && $_SESSION["autorisation"] == "emp"){
            $lesCours = Seance::getAll();
            include("Vue/Cours/cListeCours.php");
        }else{
            include("Vue/formAuth.php");
        }
        break;
    case "ajoutClasse" : 
        if(isset($_SESSION["autorisation"]) && $_SESSION["autorisation"] == "emp"){
            $lesCours = Seance::getAll();
            include("Vue/Cours/cListeCours.php");
        }else{
            include("Vue/formAuth.php");
        }
        break;

    

}
?>