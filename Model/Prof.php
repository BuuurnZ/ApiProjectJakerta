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
    

            $req->bindParam(':idInstrument', $idInstrument, PDO::PARAM_INT);
            $req->bindParam(':date', $date);
            $req->bindParam(':heureDebut', $heureDebut);
            $req->bindParam(':heureFin', $heureFin);
    
            $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Professeur');
            $req->execute();
            $resultats = $req->fetchAll();

    
            return $resultats;
    
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des professeurs disponibles " );

        }
    }

    public static function ajoutProfesseur($idUtilisateur){
        try {
            $pdo = MonPdo::getInstance();
            $req = $pdo->prepare("INSERT INTO PROFESSEUR (IDUTILISATEUR) VALUES (:idutilisateur);");
            $req->bindParam(':idutilisateur', $idUtilisateur, PDO::PARAM_STR);
            $req->execute();

        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'ajout professeur' " );
        }
    }
    public static function deleteProfesseur($idUtilisateur){
        try {
            $pdo = MonPdo::getInstance();
            $req = $pdo->prepare("DELETE FROM PROFESSEUR WHERE IDUTILISATEUR = :idutilisateur;");
            $req->bindParam(':idutilisateur', $idUtilisateur, PDO::PARAM_INT);
            $req->execute();

        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'ajout professeur' " );
        }
    }

    public static function fromUtilisateur(Utilisateur $utilisateur, $idprofesseur) {
        return new self(
            $idprofesseur,
            $utilisateur->getNOM(),
            $utilisateur->getPRENOM(),
            $utilisateur->getTELEPHONE(),
            $utilisateur->getMAIL(),
            $utilisateur->getADRESSE(),
            $utilisateur->getMDP(),
            $utilisateur->getEST_ADMIN(),
            $utilisateur->getINSTRUMENT(),
            $utilisateur->getIDUTILISATEUR(),

            
        );

        
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
