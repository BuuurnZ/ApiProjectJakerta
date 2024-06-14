<?php

class Professeur extends Utilisateur
{

    private $IDPROF;
    
    public function __construct($idprof, $nom, $prenom, $telephone, $mail, $adresse, $mdp, $est_admin, $instruments = [], $idutilisateur) {
        parent::__construct($nom, $prenom, $telephone, $mail, $adresse, $mdp, $est_admin, $instruments, $idutilisateur);
        $this->IDPROF = $idprof;
    }

    public static function getAll(){

        $req = MonPdo::getInstance()->prepare("select * from personne inner join prof on personne.ID = prof.IDPROF ;");
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Professeur');
       
        $req->execute();
        $lesResultats = $req->fetchAll();

        return $lesResultats;
    }

    public static function getProfsDisponibles($idInstrument, $datetime) {
        $pdo = MonPdo::getInstance();
    
        try {
            // Extraire la date et l'heure de la chaîne datetime
            $date = substr($datetime, 0, 10);
            $heureDebut = substr($datetime, 11, 5);
            $heureFin = date('H:i', strtotime($heureDebut . ' + 2 hours'));
            
            $req = $pdo->prepare("
                SELECT DISTINCT P.IDPROFESSEUR, U.NOM, U.PRENOM
                FROM PROFESSEUR P
                JOIN UTILISATEUR U ON P.IDUTILISATEUR = U.IDUTILISATEUR
                JOIN INSTRUMENT_UTILISATEUR IU ON U.IDUTILISATEUR = IU.IDUTILISATEUR
                JOIN INSTRUMENT I ON IU.IDINSTRUMENT = I.IDINSTRUMENT
                LEFT JOIN SEANCE S ON P.IDPROFESSEUR = S.IDPROFESSEUR AND S.DATE = :date
                WHERE I.IDINSTRUMENT = :idInstrument
                AND (
                    S.IDSEANCE IS NULL OR
                    NOT (
                        TIME(:heureDebut) BETWEEN S.HEUREDEBUT AND S.HEUREFIN OR
                        TIME(:heureFin) BETWEEN S.HEUREDEBUT AND S.HEUREFIN OR
                        TIME(S.HEUREDEBUT) BETWEEN :heureDebut AND :heureFin OR
                        TIME(S.HEUREFIN) BETWEEN :heureDebut AND :heureFin
                    )
                )
            ");
    
            // Liaison des paramètres
            $req->bindParam(':idInstrument', $idInstrument, PDO::PARAM_INT);
            $req->bindParam(':date', $date);
            $req->bindParam(':heureDebut', $heureDebut);
            $req->bindParam(':heureFin', $heureFin);
    
            // Exécution de la requête
            $req->execute();
            $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    
            // Construction des objets Professeur à partir des résultats
            $profs = [];
            foreach ($resultats as $row) {
                $prof = new Professeur(
                    $row['IDPROFESSEUR'],
                    $row['NOM'],
                    $row['PRENOM'],
                    '', // Ajoutez ici le téléphone
                    '', // Ajoutez ici l'adresse email
                    '', // Ajoutez ici l'adresse
                    '', // Ajoutez ici le mot de passe
                    0, // Ajoutez ici la valeur de est_admin
                    [], // Ajoutez ici les instruments
                    0 // Ajoutez ici l'id utilisateur
                );
                $profs[] = $prof;
            }
    
            return $profs;
    
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des professeurs disponibles : " . $e->getMessage();
            return [];
        }
    }
    
	
	
    /**
     * Get the value of IDPROF
     */ 
    public function getIDPROF()
    {
        return $this->IDPROF;
    }

    /**
     * Set the value of IDPROF
     *
     * @return  self
     */ 
    public function setIDPROF($IDPROF)
    {
        $this->IDPROF = $IDPROF;

        return $this;
    }
}
?>
