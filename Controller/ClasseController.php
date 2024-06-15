<?php

// Vérification de l'action à effectuer
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

// Vérification de l'autorisation
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
                // Si la soumission du formulaire n'a pas eu lieu, on affiche le formulaire d'ajout d'élèves
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

            case "modifier":
                $idClasse = filter_input(INPUT_GET, 'idclasse', FILTER_SANITIZE_NUMBER_INT);
                $lesEleves = Classe::getElevesDansClasse($idClasse);
                include("Vue/Classe/formModifClasse.php");
                break;

            default:
                // Si l'action n'est pas reconnue, on affiche la liste par défaut
                $lesClasses = Classe::getAll();
                include("Vue/Classe/cListeClasse.php");
                break;
        }
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        header("Location: index.php?uc=classe&action=affichage"); // Redirection vers une page d'accueil par exemple en cas d'erreur
        exit();
    }

} else {
    include("Vue/formAuth.php");
}
?>
