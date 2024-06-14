<?php

$action = $_GET["action"];


if ($action == "connexion" || $action == "deconnexion") {
    switch($action) {
        case "connexion":
            $login = $_POST["login"];
            $mdp = $_POST["mdp"];
            try {
                $result = Utilisateur::checkConnexion($login, $mdp);
            } catch(Exception $ex) {
                $_SESSION["message"] = "Login ou mot de passe incorrecte. <br>Veuillez réessayer.";
                $result = false;
            }
            
            if($result) {
                $_SESSION["user"] = $login;
                $_SESSION["autorisation"] = "emp";
                $_GET["action"] = "liste";
                include("Controller/SeanceController.php");
            } else {
                $_SESSION["message"] = "Login ou mot de passe incorrecte. <br>Veuillez réessayer.";
                include("Vue/formAuth.php");
            }
            break;

        case "deconnexion":
            Utilisateur::deconnexion();
            header("Location: index.php");
            break;
    }
} else if (isset($_SESSION["autorisation"]) && $_SESSION["autorisation"] == "emp") {

    switch($action) {
        case "accueil":
            $_GET["action"] = "liste";
            include("Controller/SeanceController.php");
            break;

        case "formInscription":
            $LesInstruments = Instrument::getAll();
            include("Vue/Utilisateur/formAjoutPersonne.php");
            break;

        case "inscription":

            $_GET["action"] = "liste";
            if(!isset($_POST["instruments"])) {
                $instruments = []; 
            } else {
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

            if($_POST['role'] == 2) {
                Utilisateur::ajouterPersonne($utilisateur, "ELEVE");
            } elseif ($_POST['role'] == 3) {
                Utilisateur::ajouterPersonne($utilisateur, "PROFESSEUR");
            } else {
                Utilisateur::ajouterPersonne($utilisateur, "");
            }

                include("Controller/SeanceController.php");
                break;

            case "supprimer":
                $_GET["action"] = "liste";
                Utilisateur::supprimerUtilisateur($_GET['idutilisateur']);
                include("Controller/SeanceController.php");
                break;
    
    }
} else {
    include("Vue/formAuth.php");
}
?>
