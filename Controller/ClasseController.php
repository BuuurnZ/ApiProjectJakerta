<?php

$action = $_GET["action"];

if(isset($_SESSION["autorisation"]) && $_SESSION["autorisation"] == "emp"){

    switch($action){

        case "affichage":
            $lesClasses = Classe::getAll();
            include("Vue/Classe/cListeClasse.php");
        break;

        case "creation":
            $lesInstruments = Instrument::getAll(); 
            if(isset($_POST['idInstrument'])){
                $idInstruments = $_POST['idInstrument'];
                $lesEleves = Eleve::getElevesSansClasseParInstrument($idInstruments);
            }
            include("Vue/Classe/formAjoutClasse.php");   
        break;

        case"ajoutEleve":     
            if(isset($_POST['eleves'])){
                $_GET["action"] = "affichage";
                Classe::ajouterClasseAvecEleves($_POST['eleves'], $_POST['idinstrument']);
            }
            include("Controller/ClasseController.php");
        break;
        
        case"supprimer":
            $_GET["action"] = "affichage";
            if(isset($_GET['idClasse'])){
                Classe::supprimerClasse($_GET['idClasse']);
            }
            include("Controller/ClasseController.php");
        break;

        case"modifier":
            $lesEleves = Classe::getElevesDansClasse($_GET['idclasse']);
            include("Vue/Classe/formModifClasse.php");  
        break;
    }

}
else{
    include("Vue/formAuth.php");
}
?>