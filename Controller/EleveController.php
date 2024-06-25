<?php

$action = $_GET["action"];
if(isset($_SESSION["autorisation"]) && $_SESSION["autorisation"] == "eleve"){
    switch($action){

        case "accueil":
            $lesSeanceEleves = Eleve::recuperationSeance($_SESSION["user"] );
            $lesSeance = Seance::getAll();
            
            foreach($lesSeanceEleves as $seanceEleve){
                foreach($lesSeance as $seance){
                    if($seanceEleve->getIdSeance() == $seance->getIdSeance()){
                        $lesCours[] = $seance;
                    }
                }
            }

            include("Vue/Eleve/eleveAccueil.php");
            break;

        
    }
} else {
    include("Vue/formAuth.php");
}
?>
