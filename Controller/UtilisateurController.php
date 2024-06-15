<?php

$action = filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING);

if ($action == "connexion" || $action == "deconnexion") {
    try {
        switch($action) {
            case "connexion":
                $login = filter_input(INPUT_POST, "login", FILTER_SANITIZE_STRING);
                $mdp = filter_input(INPUT_POST, "mdp", FILTER_SANITIZE_STRING);
                
                $result = Utilisateur::checkConnexion($login, $mdp);
                
                if($result) {
                    $_SESSION["user"] = $login;
                    $_SESSION["autorisation"] = "emp";
                    header("Location: index.php?uc=eleve&action=liste");
                    exit();
                } else {
                    $_SESSION["message"] = "Login ou mot de passe incorrect. Veuillez réessayer.";
                    include("Vue/formAuth.php");
                    exit();
                }
                break;
    
            case "deconnexion":
                Utilisateur::deconnexion();
                header("Location: index.php");
                exit();
                break;
        }
    } catch(Exception $ex) {
        $_SESSION["message"] = $ex->getMessage();
        include("Vue/formAuth.php");
        exit();
    }
} else if (isset($_SESSION["autorisation"]) && $_SESSION["autorisation"] == "emp") {
    try {
        switch($action) {
            case "accueil":
                $_GET["action"] = "liste";
                include("Controller/SeanceController.php");
                exit();
                break;
    
            case "formInscription":
                $LesInstruments = Instrument::getAll();
                include("Vue/Utilisateur/formAjoutPersonne.php");
                exit();
                break;
    
                case "inscription":
                    $_GET["action"] = "liste";
                    $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_STRING);
                    $prenom = filter_input(INPUT_POST, "prenom", FILTER_SANITIZE_STRING);
                    $telephone = filter_input(INPUT_POST, "telephone", FILTER_SANITIZE_STRING);
                    $mail = filter_input(INPUT_POST, "mail", FILTER_SANITIZE_EMAIL);
                
                    /*  verifier le format du mail 
                    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                        $_SESSION['message'] =  "L'adresse email n'est pas valide.";
                        include("Vue/Utilisateur/formAjoutPersonne.php");
                        exit();
                    }
                    */
                    $adresse = filter_input(INPUT_POST, "adresse", FILTER_SANITIZE_STRING);
                    $mdp = filter_input(INPUT_POST, "mdp", FILTER_SANITIZE_STRING);
                    $role = filter_input(INPUT_POST, "role", FILTER_VALIDATE_INT);
                    $instruments = isset($_POST["instruments"]) ? $_POST["instruments"] : [];
                
                    $utilisateur = new Utilisateur(
                        $nom,
                        $prenom,
                        $telephone,
                        $mail,
                        $adresse,
                        $mdp,
                        $role,
                        $instruments,
                        NULL
                    );
                
                    if ($role == 2) {
                        Utilisateur::ajouterPersonne($utilisateur, "ELEVE");
                    } elseif ($role == 3) {
                        Utilisateur::ajouterPersonne($utilisateur, "PROFESSEUR");
                    } else {
                        Utilisateur::ajouterPersonne($utilisateur, "");
                    }
                
                    include("Controller/SeanceController.php");
                    exit();
                    break;
            case "supprimer":
                $_GET["action"] = "liste";
                $idutilisateur = filter_input(INPUT_GET, "idutilisateur", FILTER_VALIDATE_INT);
                if ($idutilisateur !== false && $idutilisateur !== null) {
                    Utilisateur::supprimerUtilisateur($idutilisateur);
                }
                header("Location: index.php?uc=eleve&action=liste");
                exit();
                break;
        }
    } catch(Exception $ex) {
        $_SESSION["message"] = $ex->getMessage();
        include("Vue/formAuth.php");
        exit();
    }
} else {
    include("Vue/formAuth.php");
    exit();
}
?>
