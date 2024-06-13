<?php

$action = $_GET["action"];

switch($action){

    case "accueil":
        if(isset($_SESSION["autorisation"]) && $_SESSION["autorisation"] == "emp"){
            $_GET["action"] = "liste";

            include("Controller/SeanceController.php");
        }else{
            include("View/formAuth.php");
        }
        
        break;
    case "formInscription" : 
        if(isset($_SESSION["autorisation"]) && $_SESSION["autorisation"] == "emp"){
            $LesInstruments = Instrument::getAll();
            include("Vue/formAjoutPersonne.php");
        }else{
            include("Vue/formAuth.php");
        }
        break;

    case "inscription" : 
        if(isset($_SESSION["autorisation"]) && $_SESSION["autorisation"] == "emp"){
            $_GET["action"] = "liste";
            if(!isset($_POST["instruments"])){
                $instruments = []; 
            }
            else {
                $instruments = $_POST["instruments"];
            }
            $utilisateur = new Utilisateur(
                $_POST['nom'],
                $_POST['prenom'],
                $_POST['telephone'],
                $_POST['mail'],
                $_POST['adresse'],
                $_POST['mdp'],
                $_POST['role'],
                $instruments,
                NULL

            );

            if($_POST['role'] == 2){
                Utilisateur::ajouterPersonne($utilisateur, "ELEVE");
            }
            elseif ($_POST['role'] == 3) {
                Utilisateur::ajouterPersonne($utilisateur, "PROFESSEUR");
            }
            else{
                Utilisateur::ajouterPersonne($utilisateur, "");
            }

            include("Controller/SeanceController.php");
        }else{
            include("Vue/formAuth.php");
        }
        
        break;
        

    case "connexion":
        $login = $_POST["login"];
        $mdp = $_POST["mdp"];
        try{
            $result = Utilisateur::checkConnexion($login, $mdp);
        }catch(Exception $ex){
            $_SESSION["message"] = "Login ou mot de passe incorrecte. <br>Veuillez réessayer.";
        }
        
        if($result){
            
            $_SESSION["user"] = $login;
            $_SESSION["autorisation"] = "emp";
            $_GET["action"] = "liste";
            include("Controller/SeanceController.php");
        }else{
            $_SESSION["message"] = "Login ou mot de passe incorrecte. <br>Veuillez réessayer.";
            include("Vue/formAuth.php");
        }
        break;

    case "deconnexion":
        Employe::deconnexion();
        header("Location: index.php");

}
?>