<?php

$action = filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING);

if ($action == "connexion" || $action == "deconnexion") {
    try {
        switch($action) {
            case "connexion":
                $login = filter_input(INPUT_POST, "login", FILTER_SANITIZE_STRING);
                $mdp = filter_input(INPUT_POST, "mdp", FILTER_SANITIZE_STRING);
                
                $utilisateur = Utilisateur::checkConnexion($login, $mdp);
                

                if( $utilisateur->getEST_ADMIN() == 1){
                    $_SESSION["autorisation"] = "emp";
                    header("Location: index.php?uc=utilisateur&action=liste");
                    exit();
                } 
                
                else {
                    $role = Utilisateur::recupererRole($utilisateur->getIdutilisateur());

                    if($role["ROLE"] == "ELEVE"){
                        $_SESSION["user"] = $utilisateur->getIdutilisateur();
                        $_SESSION["autorisation"] = "eleve";
                        header("Location: index.php?uc=eleve&action=accueil");
                        exit();
                    }
                    else{
                        $_SESSION["user"] = $utilisateur->getIdutilisateur();
                        $_SESSION["autorisation"] = "professeur";
                        header("Location: index.php?uc=professeur&action=accueil");
                        exit();
                    }
               
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
            
            case "liste":
                $lesEleves = Utilisateur::getAll();

                include("Vue/Utilisateur/cListeAdh.php");
                break;

            case "recherche":
                $recherche = filter_input(INPUT_POST, "recherche", FILTER_SANITIZE_STRING);
                $lesEleves = Utilisateur::rechercheUtilisateur($recherche);
                include("Vue/Utilisateur/cListeAdh.php");
                break;
                
    
            case "formInscription":
                $LesInstruments = Instrument::getAll();
                include("Vue/Utilisateur/formAjoutPersonne.php");
                break;
            
            case"formModifier": 
                $utilisateur = Utilisateur::getUtilisateur(filter_input(INPUT_GET, "idutilisateur", FILTER_VALIDATE_INT));

                $instruments = !empty($utilisateur->INSTRUMENTS) ? explode(', ', $utilisateur->INSTRUMENTS) : [];
                $utilisateur->setINSTRUMENT($instruments);

                if(!empty($utilisateur->IDPROFESSEUR) ){
                    $utilisateur = Professeur::fromUtilisateur($utilisateur, $utilisateur->IDPROFESSEUR);
                }
                elseif(!empty($utilisateur->IDELEVE != NULL))
                {
                    $utilisateur = Eleve::fromUtilisateur($utilisateur, $utilisateur->IDPROFESSEUR);
                }
                $LesInstruments = Instrument::getAll();
                include("Vue/Utilisateur/formModifPersonne.php");
                break;

            
            case "modifier":
                    $idutilisateur = filter_input(INPUT_GET, "idutilisateur", FILTER_VALIDATE_INT);
                    
                    if (!$idutilisateur) {

                        header("Location: index.php"); 
                        exit();
                    }
                    
                    
                    $ancienRoleData = Utilisateur::recupererRole($idutilisateur);
                    $ancienRole = $ancienRoleData['ROLE']; 

                    $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_STRING);
                    $prenom = filter_input(INPUT_POST, "prenom", FILTER_SANITIZE_STRING);
                    $telephone = filter_input(INPUT_POST, "telephone", FILTER_SANITIZE_STRING);
                    $mail = filter_input(INPUT_POST, "mail", FILTER_SANITIZE_EMAIL);
                    $adresse = filter_input(INPUT_POST, "adresse", FILTER_SANITIZE_STRING);
                    $mdp = filter_input(INPUT_POST, "mdp", FILTER_SANITIZE_STRING);
                    $role = filter_input(INPUT_POST, "role", FILTER_VALIDATE_INT);
                    $instruments = isset($_POST["instruments"]) ? $_POST["instruments"] : [];
                
                    /*$resultatValidation = verificationFormat($nom, $prenom, $telephone, $mail, $adresse, $mdp, $role, $instruments);
                
                    if ($resultatValidation !== true) {
                        $utilisateur = Utilisateur::getUtilisateur(filter_input(INPUT_GET, "idutilisateur", FILTER_VALIDATE_INT));
                        $LesInstruments = Instrument::getAll();
                        include("Vue/Utilisateur/formModifPersonne.php"); 
                        exit();
                    }*/

                
                    $utilisateur = new Utilisateur(
                        $nom,
                        $prenom,
                        $telephone,
                        $mail,
                        $adresse,
                        $mdp,
                        $role,
                        $instruments,
                        $idutilisateur
                    );
                    
                    if ($role == 2) {
                        Utilisateur::modifierPersonne($utilisateur, "ELEVE", $ancienRole);
                    } elseif ($role == 3) {
                        Utilisateur::modifierPersonne($utilisateur, "PROFESSEUR", $ancienRole);
                    } else {
                        Utilisateur::modifierPersonne($utilisateur, "", $ancienRole);
                    }

                    header("Location: index.php?uc=utilisateur&action=liste"); 
                    exit();
                    
                    break;
        

            
            case "inscription":

                
                $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_STRING);
                $prenom = filter_input(INPUT_POST, "prenom", FILTER_SANITIZE_STRING);
                $telephone = filter_input(INPUT_POST, "telephone", FILTER_SANITIZE_STRING);
                $mail = filter_input(INPUT_POST, "mail", FILTER_SANITIZE_EMAIL);
                $adresse = filter_input(INPUT_POST, "adresse", FILTER_SANITIZE_STRING);
                $mdp = filter_input(INPUT_POST, "mdp", FILTER_SANITIZE_STRING);
                $role = filter_input(INPUT_POST, "role", FILTER_VALIDATE_INT);
                $instrument = filter_input(INPUT_POST, 'instruments', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

                if ($instrument === null || $instrument === false) {
                    $instrument = [];
                }
                
               /* $resultatValidation = verificationFormatSansMdp($nom, $prenom, $telephone, $mail, $adresse, $role, $instruments);
                
                if ($resultatValidation !== true) {
                    $LesInstruments = Instrument::getAll();
                    include("Vue/Utilisateur/formAjoutPersonne.php");
                    exit();
                }*/
                
                $utilisateur = new Utilisateur(
                    $nom,
                    $prenom,
                    $telephone,
                    $mail,
                    $adresse,
                    $mdp,
                    $role,
                    $instrument,
                    NULL
                );
                
                $idNewUser = Utilisateur::ajouterPersonne($utilisateur); 

                if ($role == 2) {
                    Eleve::ajoutEleve($idNewUser);
                    foreach ($instrument as $instruments){
                        Utilisateur::ajoutInstrumentUtilisateur($idNewUser, $instruments);
                    }

                } elseif ($role == 3) {
                    Professeur::ajoutProfesseur($idNewUser); 
                    foreach ($instrument as $instruments){
                        
                        Utilisateur::ajoutInstrumentUtilisateur($idNewUser, $instruments);
                    }
                } 

                header("Location: index.php?uc=utilisateur&action=liste");
                exit();
                break;

            case "supprimer":

                $idutilisateur = filter_input(INPUT_GET, "idutilisateur", FILTER_VALIDATE_INT);
                if ($idutilisateur !== false && $idutilisateur !== null) {
                    Utilisateur::supprimerUtilisateur($idutilisateur);
                }
                header("Location: index.php?uc=utilisateur&action=liste");
                exit();
                break;
        }
    } catch(Exception $ex) {
        $_SESSION["message"] = $ex->getMessage();
        //include("Vue/formAuth.php");
        exit();
    }
} else {
    include("Vue/formAuth.php");
    exit();
}

function verificationFormat($nom, $prenom, $telephone, $mail, $adresse, $mdp, $role, $instruments) {
    $erreurs = [];

    if (!preg_match("/^[a-zA-ZÀ-ÿ\- ]+$/", $nom)) {
        $erreurs['nom'] = "Le nom ne doit contenir que des lettres.";
    }

    if (!preg_match("/^[a-zA-ZÀ-ÿ\- ]+$/", $prenom)) {
        $erreurs['prenom'] = "Le prénom ne doit contenir que des lettres.";
    }

    if (!preg_match("/^\d{10}$/", $telephone)) {
        $erreurs['telephone'] = "Le numéro de téléphone doit contenir exactement 10 chiffres.";
    }

    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $erreurs['mail'] = "L'adresse email n'est pas valide.";
    }

    if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&.\/])[A-Za-z\d@$!%*?&.\/]{8,}$/", $mdp)) {
        $erreurs['mdp'] = "Le mot de passe doit contenir au moins 8 caractères, incluant au moins une majuscule, une minuscule, un chiffre et au moins un des caractères spéciaux suivants : @, $, !, %, *, ?, &, ., /.";
    }

    if (!in_array($role, [1, 2, 3])) {
        $erreurs['role'] = "Le rôle n'est pas valide.";
    }

    if ($role == 1 && !empty($instruments)) {
        $erreurs['instruments'] = "Les instruments ne doivent pas être sélectionnés pour ce rôle.";
    }

    if ($role == 2 && (empty($instruments) || count($instruments) > 1)) {
        $erreurs['instruments'] = "Vous devez sélectionner exactement un instrument pour ce rôle.";
    }

    if ($role == 3 && (!is_array($instruments) || empty($instruments))) {
        $erreurs['instruments'] = "Vous devez sélectionner au moins un instrument pour ce rôle.";
    }

    if (empty($erreurs)) {
        return true;
    } else {

        $_SESSION['erreurs'] = $erreurs;
        return false;
    }
}

function verificationFormatSansMdp($nom, $prenom, $telephone, $mail, $adresse, $role, $instruments) {
    $erreurs = [];

    if (!preg_match("/^[a-zA-ZÀ-ÿ\- ]+$/", $nom)) {
        $erreurs['nom'] = "Le nom ne doit contenir que des lettres.";
    }

    if (!preg_match("/^[a-zA-ZÀ-ÿ\- ]+$/", $prenom)) {
        $erreurs['prenom'] = "Le prénom ne doit contenir que des lettres.";
    }

    if (!preg_match("/^\d{10}$/", $telephone)) {
        $erreurs['telephone'] = "Le numéro de téléphone doit contenir exactement 10 chiffres.";
    }

    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $erreurs['mail'] = "L'adresse email n'est pas valide.";
    }

    if (!in_array($role, [1, 2, 3])) {
        $erreurs['role'] = "Le rôle n'est pas valide.";
    }

    if ($role == 1 && !empty($instruments)) {
        $erreurs['instruments'] = "Les instruments ne doivent pas être sélectionnés pour ce rôle.";
    }

    if ($role == 2 && (empty($instruments) || count($instruments) > 1)) {
        $erreurs['instruments'] = "Vous devez sélectionner exactement un instrument pour ce rôle.";
    }

    if ($role == 3 && (!is_array($instruments) || empty($instruments))) {
        $erreurs['instruments'] = "Vous devez sélectionner au moins un instrument pour ce rôle.";
    }

    if (empty($erreurs)) {
        return true;
    } else {

        $_SESSION['erreurs'] = $erreurs;
        return false;
    }
}

?>
