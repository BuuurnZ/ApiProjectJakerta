<?php
    include("Vue/header.php");
    //Connexion à la base de données (uniquement dans index.php)
    include("Model/monPdo.php");

    //Inclure tous les Models
    include("Model/Utilisateur.php");
    include("Model/Eleve.php");
    include("Model/Seance.php");
    include("Model/Prof.php");
    include("Model/Instrument.php");
    include("Model/Classe.php");

    if(empty($_GET["uc"])){
        $uc = "authentification";
    }else{
        $uc = $_GET["uc"];
    }

    try {
        switch($uc){

            case "authentification":
                include("Vue/formAuth.php");
                break;
            case "utilisateur":
                include("Controller/UtilisateurController.php");
                break;
            case "seance": 
                include("Controller/SeanceController.php");
                break;
            case "eleve":
                include("Controller/EleveController.php");
                break;
            case "classe": 
                include("Controller/ClasseController.php");
                break;
        
        }
    }
     catch (Exception $ex) {
        echo "Erreur: " . $ex->getMessage();
    }
    

    include("Vue/footer.php");
?>