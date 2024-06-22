<?php


$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);


if (isset($_SESSION["autorisation"]) && $_SESSION["autorisation"] == "emp") {

    try {
        switch ($action) {

            case "liste":
                $lesInstruments = Instrument::getAll();
                include("Vue/Instrument/cListeInstrument.php");
                break;

            case "supprimer":
                $instrument = filter_input(INPUT_POST, 'idinstrument', FILTER_SANITIZE_NUMBER_INT);
                Instrument::supprimerInstrument($instrument);
                header("Location: index.php?uc=instrument&action=liste"); 
                break;

            case "ajouter":
                $instrument = filter_input(INPUT_POST, 'idinstrument', FILTER_SANITIZE_NUMBER_INT);
                Instrument::supprimerInstrument($instrument);
                header("Location: index.php?uc=instrument&action=liste"); 
                break;
                
        }
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        header("Location: index.php?uc=instrument&action=liste"); 
        exit();
    }

} else {
    include("Vue/formAuth.php");
}
?>
