<?php

$action = $_GET["action"];

if(isset($_SESSION["autorisation"]) && $_SESSION["autorisation"] == "emp"){

    switch($action){
        case "liste":
            $lesCours = Seance::getAll();
            include("Vue/Cours/cListeCours.php");
        break;

        case "ajouter" : 
            $lesInstruments = Instrument::getAll(); 
            if(isset($_POST['idInstrument'])){
                $idInstruments = $_POST['idInstrument'];
                $date = new DateTime();
        
                $formattedDate = $date->format('Y-m-d\TH:i');
        
                $date->modify('+1 year');
        
                $formattedDatePlusOneYear = $date->format('Y-m-d\TH:i');
            }
            if(isset($_POST['dateSeance'])) {
                $dateSeance = $_POST['dateSeance'];
        
                $timestamp = strtotime($dateSeance);

                $jour = date('d', $timestamp); 
                $mois = date('m', $timestamp); 
                $annee = date('Y', $timestamp); 
                $heure = date('H:i', $timestamp);
                    
                $profsDisponibles = Professeur::getProfsDisponibles($idInstruments, $dateSeance);
                $classesDisponibles = Classe::getClassesDisponibles($idInstruments, $dateSeance);

            } 
            if(isset($_POST['idInstrument']) && isset($_POST['dateSeance']) && isset($_POST['idProfesseur']) && isset($_POST['idClasse']) ){
                $_GET["action"] = "liste";
                Seance::ajouterSeance($_POST['idProfesseur'], $_POST['idClasse'], $_POST['dateSeance']);
                include("Controller/SeanceController.php");
                break;
            }

            include("Vue/Cours/formAjoutSeance.php");
            
        break;

    }
}
else {
    include("Vue/formAuth.php");
}
?>