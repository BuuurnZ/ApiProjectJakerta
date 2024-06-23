<?php



$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

if (isset($_SESSION["autorisation"]) && $_SESSION["autorisation"] === "emp") {
    try {
        switch ($action) {
            case "liste":
                $lesCours = Seance::getAll();
                include("Vue/Cours/cListeCours.php");
                break;

            case "ajouter":
                $lesInstruments = Instrument::getAll();

                $idInstruments = filter_input(INPUT_POST, 'idInstrument', FILTER_SANITIZE_NUMBER_INT);
                $dateSeance = filter_input(INPUT_POST, 'dateSeance', FILTER_SANITIZE_STRING);

                if ($idInstruments && !$dateSeance) {
                    $date = new DateTime();
                    $formattedDate = $date->format('Y-m-d\TH:i');
                    $date->modify('+1 year');
                    $formattedDatePlusOneYear = $date->format('Y-m-d\TH:i');
                }

                if ($dateSeance) {
                    $timestamp = strtotime($dateSeance);
                    $jour = date('d', $timestamp); 
                    $mois = date('m', $timestamp); 
                    $annee = date('Y', $timestamp); 
                    $heure = date('H:i', $timestamp);
                    
                    $profsDisponibles = Professeur::getProfsDisponibles($idInstruments, $dateSeance);
                    
                    foreach($profsDisponibles as $prof){
                        $prof = Professeur::fromUtilisateur($prof, $prof->IDPROFESSEUR);
                        $profsDispo[] = $prof;
                    }

                    $classesDisponibles = Classe::getClassesDisponibles($idInstruments, $dateSeance);
                }

                $idProfesseur = filter_input(INPUT_POST, 'idProfesseur', FILTER_SANITIZE_NUMBER_INT);
                $idClasse = filter_input(INPUT_POST, 'idClasse', FILTER_SANITIZE_NUMBER_INT);

                if ($idInstruments && $dateSeance && $idProfesseur && $idClasse) {

                    $date = substr($dateSeance, 0, 10);
                    $heureDebut = substr($dateSeance, 11, 5);
                    $heureFin = date('H:i', strtotime($dateSeance . ' + 2 hours'));
    

                    $resultatVerifInstrument = Seance::verifInstrumentProfEtClasse($idClasse, $idProfesseur);
                    if ($resultatVerifInstrument['nbInstruments'] != 1) {
                        throw new Exception("Le professeur et la classe doivent avoir le même instrument.");
                    }

                    $resultatConflitCours = Seance::verifConflitProfEtClasse($idClasse, $date, $heureDebut, $heureFin);

                    if ($resultatVerifConflit['nbConflits'] > 0) {
                        throw new Exception("Il y a déjà un cours prévu pour cette classe à ce créneau horaire le même jour.");
                    }

                    Seance::ajouterSeance($idProfesseur, $idClasse, $dateSeance);
                    header("Location: index.php?uc=seance&action=liste");
                    break;
                    exit();
                }

                include("Vue/Cours/formAjoutSeance.php");
                break;
            
                case "supprimer":
                    $idSeance = filter_input(INPUT_GET, "idSeance", FILTER_VALIDATE_INT);
                    if ($idSeance !== false && $idSeance !== null) {
                        Seance::supprimerSeance($idSeance);
                    }
                    header("Location: index.php?uc=seance&action=liste");
                    exit();
                    break;

                case "afficherMembre" :
                    $idSeance = filter_input(INPUT_GET, "idSeance", FILTER_VALIDATE_INT);
                    $lesElevesListe = Seance::afficherClasseSeance($idSeance);
                  
                    foreach($lesElevesListe as $eleve){
                        $eleve = Eleve::fromUtilisateur($eleve, $eleve->IDELEVE);
                        $lesEleves[] = $eleve;
                    }

                    include("Vue/Cours/cListeMembreSeance.php");
                    break;


        }
    } catch (Exception $e) {
        $_SESSION['message'] = "Erreur : " . $e->getMessage();
        echo($e->getMessage());
    }
} else {
    include("Vue/formAuth.php");
}
?>
