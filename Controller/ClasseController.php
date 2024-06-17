<?php


$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);


if (isset($_SESSION["autorisation"]) && $_SESSION["autorisation"] == "emp") {

    try {
        switch ($action) {

            case "affichage":
                $lesClasses = Classe::getAll();
                include("Vue/Classe/cListeClasse.php");
                break;

            case "creation":
                $lesInstruments = Instrument::getAll();
                if (isset($_POST['idInstrument'])) {
                    $idInstruments = filter_input(INPUT_POST, 'idInstrument', FILTER_SANITIZE_NUMBER_INT);
                    $lesEleves = Eleve::getElevesSansClasseParInstrument($idInstruments);
                }
                include("Vue/Classe/formAjoutClasse.php");
                break;

            case "ajoutEleve":
                if (isset($_POST['eleves'])) {
                    $eleves = filter_input(INPUT_POST, 'eleves', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
                    $idInstrument = filter_input(INPUT_POST, 'idinstrument', FILTER_SANITIZE_NUMBER_INT);
                    Classe::ajouterClasseAvecEleves($eleves, $idInstrument);
                    $_SESSION['message'] = "Classe créée avec succès et élèves ajoutés.";
                    header("Location: index.php?uc=classe&action=affichage");
                    exit();
                }

                include("Vue/Classe/formAjoutClasse.php");
                break;

            case "supprimer":

                if (isset($_GET['idClasse'])) {
                    $idClasse = filter_input(INPUT_GET, 'idClasse', FILTER_SANITIZE_NUMBER_INT);
                    Classe::supprimerClasse($idClasse);
                    $_SESSION['message'] = "Classe supprimée avec succès.";
                }
                header("Location: index.php?uc=classe&action=affichage");
                exit();
                break;

            case "modifierClasse":

                $idClasse = filter_input(INPUT_POST, 'idclasse', FILTER_SANITIZE_NUMBER_INT);

                if (isset($_POST['eleves'])) {
                    
                    $idEleve = filter_input(INPUT_POST, 'eleves',  FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
                    Classe::modifierUneClasse($idClasse, $idEleve);
                    $_SESSION['message'] = "Classe modifier avec succès ";
                    header("Location: index.php?uc=classe&action=affichage");
                    exit();
                }
                else{

                    header("Location: index.php?uc=classe&action=supprimer&idClasse=$idClasse");
                    exit();
                }
                include("Vue/Classe/formModifClasse.php");
                break;
            
                case "modifier":
                    $idClasse = filter_input(INPUT_GET, 'idclasse', FILTER_SANITIZE_NUMBER_INT);
                    $idInstruments = filter_input(INPUT_GET, 'idinstrument', FILTER_SANITIZE_NUMBER_INT);
                    $elevesDansLaClasse = Classe::getElevesDansClasse($idClasse);
                    $elevesSansClasse = Eleve::getElevesSansClasseParInstrument($idInstruments);
                    $lesEleves = array_merge($elevesDansLaClasse, $elevesSansClasse);
    
                    include("Vue/Classe/formModifClasse.php");
                    break;
    
            
            default:

                $lesClasses = Classe::getAll();
                include("Vue/Classe/cListeClasse.php");
                break;
        }
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        header("Location: index.php?uc=classe&action=affichage"); 
        exit();
    }

} else {
    include("Vue/formAuth.php");
}
?>
