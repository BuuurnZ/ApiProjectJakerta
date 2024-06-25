<?php


$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);


if (isset($_SESSION["autorisation"]) && $_SESSION["autorisation"] == "emp") {

    try {
        switch ($action) {

            case "affichage":
                $lesClassesListe = Classe::getAll();
                foreach($lesClassesListe as $classe){
                    $listeEleve = Classe::getElevesDansClasse($classe->getIDCLASSE());
                    foreach($listeEleve as $eleve){
                        $lesEleve = Eleve::fromUtilisateur($eleve, $eleve->IDELEVE);
                        $classe->addEleve($lesEleve);
                    }
                    $lesClasses[] = $classe;
                }

                include("Vue/Classe/cListeClasse.php");
                break;

            case "creation":
                $lesInstruments = Instrument::getAll();
                if (isset($_POST['idInstrument'])) {
                    $idInstruments = filter_input(INPUT_POST, 'idInstrument', FILTER_SANITIZE_NUMBER_INT);
                    $lesElevesListe = Eleve::getElevesSansClasseParInstrument($idInstruments);
                    foreach($lesElevesListe as $eleve){
                        $eleve = Eleve::fromUtilisateur($eleve, $eleve->IDELEVE);
                        $lesEleves[] = $eleve;
                    }
                }
                include("Vue/Classe/formAjoutClasse.php");
                break;

            case "ajoutEleve":
                if (isset($_POST['eleves'])) {
                    $eleves = filter_input(INPUT_POST, 'eleves', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
                    $idInstrument = filter_input(INPUT_POST, 'idinstrument', FILTER_SANITIZE_NUMBER_INT);


                    $idClasse = Classe::ajoutClasse($idInstrument);
                    foreach($eleves as $ideleve){
                        Classe::ajoutClasseEleve($idClasse, $ideleve);

                    }
                    $_SESSION['Sucess'] = "Classe créée avec succès et élèves ajoutés.";
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
                $_SESSION['Sucess'] = "Classe supprimer avec succès";
                header("Location: index.php?uc=classe&action=affichage");
                exit();
                break;

            case "modifierClasse":

                $idClasse = filter_input(INPUT_POST, 'idclasse', FILTER_SANITIZE_NUMBER_INT);

                if (isset($_POST['eleves'])) {
                    
                    $idEleve = filter_input(INPUT_POST, 'eleves',  FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
                    Classe::supprimerClasseEleve($idClasse);
                    foreach($idEleve as $eleve){
                        Classe::ajoutClasseEleve($idClasse, $eleve);
                    }
                    $_SESSION['Sucess'] = "Classe modifier avec succès ";
                    header("Location: index.php?uc=classe&action=affichage");
                    exit();
                }

                else{
                    $_SESSION['Sucess'] = "Classe supprimer avec succès";
                    header("Location: index.php?uc=classe&action=supprimer&idClasse=$idClasse");
                    exit();
                }
                include("Vue/Classe/formModifClasse.php");
                break;
            
                case "modifier":
                    $idClasse = filter_input(INPUT_GET, 'idclasse', FILTER_SANITIZE_NUMBER_INT);
                    $idInstruments = filter_input(INPUT_GET, 'idinstrument', FILTER_SANITIZE_NUMBER_INT);

                    $elevesDansLaClasseListe = Classe::getElevesDansClasse($idClasse);
                    $elevesSansClasse = [];

                    foreach($elevesDansLaClasseListe as $eleve){
                        $eleve = Eleve::fromUtilisateur($eleve, $eleve->IDELEVE);
                        $elevesDansLaClasse[] = $eleve;
                    }

                    $elevesSansClasseListe = Eleve::getElevesSansClasseParInstrument($idInstruments);
                    foreach($elevesSansClasseListe as $eleve){
                        $eleve = Eleve::fromUtilisateur($eleve, $eleve->IDELEVE);
                        $elevesSansClasse[] = $eleve;
                    }
                    
                    $lesEleves = array_merge($elevesDansLaClasse, $elevesSansClasse);
                    

                    include("Vue/Classe/formModifClasse.php");
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
