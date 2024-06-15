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
                    $classesDisponibles = Classe::getClassesDisponibles($idInstruments, $dateSeance);
                }

                $idProfesseur = filter_input(INPUT_POST, 'idProfesseur', FILTER_SANITIZE_NUMBER_INT);
                $idClasse = filter_input(INPUT_POST, 'idClasse', FILTER_SANITIZE_NUMBER_INT);

                if ($idInstruments && $dateSeance && $idProfesseur && $idClasse) {
                    $_GET["action"] = "liste";
                    Seance::ajouterSeance($idProfesseur, $idClasse, $dateSeance);
                    header("Location: index.php?uc=seance&action=liste");
                    break;
                }

                include("Vue/Cours/formAjoutSeance.php");
                break;
        }
    } catch (Exception $e) {
        $_SESSION['message'] = "Erreur : " . $e->getMessage();
    }
} else {
    include("Vue/formAuth.php");
}
?>
