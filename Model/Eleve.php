<?php

class Eleve extends Utilisateur
{
    private $IDELEVE;

    public function __construct($ideleve = null, $nom = null, $prenom = null, $telephone = null, $mail = null, $adresse = null, $mdp = null, $est_admin = null, $instruments = [], $idutilisateur = null)
    {
        parent::__construct($nom, $prenom, $telephone, $mail, $adresse, $mdp, $est_admin, $instruments, $idutilisateur);
        $this->IDELEVE = $ideleve;
    }

    public static function getAll() {
        try {
            $req = MonPdo::getInstance()->prepare("
                SELECT U.IDUTILISATEUR, E.IDELEVE, U.NOM, U.PRENOM, U.TELEPHONE, U.ADRESSE, U.MAIL, GROUP_CONCAT(I.LIBELLE SEPARATOR ', ') AS INSTRUMENTS
                FROM ELEVE E
                JOIN UTILISATEUR U ON E.IDUTILISATEUR = U.IDUTILISATEUR
                LEFT JOIN INSTRUMENT_UTILISATEUR IU ON U.IDUTILISATEUR = IU.IDUTILISATEUR
                LEFT JOIN INSTRUMENT I ON IU.IDINSTRUMENT = I.IDINSTRUMENT
                GROUP BY E.IDELEVE, U.IDUTILISATEUR, U.NOM, U.PRENOM, U.TELEPHONE, U.ADRESSE, U.MAIL
            ");
            $req->execute();
            $eleves = $req->fetchAll(PDO::FETCH_ASSOC);
    
            $listeEleves = [];
            foreach ($eleves as $eleve) {
                $instruments = explode(', ', $eleve['INSTRUMENTS']);
                $listeEleves[] = new Eleve($eleve['IDELEVE'], $eleve['NOM'], $eleve['PRENOM'], $eleve['TELEPHONE'], $eleve['MAIL'], $eleve['ADRESSE'], '', 0, $instruments, $eleve['IDUTILISATEUR']);
            }
            return $listeEleves;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des élèves : " . $e->getMessage());
        }
    }
    
    public static function getElevesSansClasseParInstrument($instrument) {
        try {
            $pdo = MonPdo::getInstance();
            $req = $pdo->prepare("
                SELECT
                    E.IDELEVE,
                    U.IDUTILISATEUR,
                    U.NOM,
                    U.PRENOM,
                    U.TELEPHONE,
                    U.ADRESSE,
                    U.MAIL
                FROM
                    UTILISATEUR U
                JOIN
                    INSTRUMENT_UTILISATEUR IU ON U.IDUTILISATEUR = IU.IDUTILISATEUR
                JOIN
                    INSTRUMENT I ON IU.IDINSTRUMENT = I.IDINSTRUMENT
                LEFT JOIN
                    ELEVE E ON U.IDUTILISATEUR = E.IDUTILISATEUR
                LEFT JOIN
                    CLASSE_ELEVE CE ON E.IDELEVE = CE.IDELEVE
                LEFT JOIN
                    CLASSE C ON CE.IDCLASSE = C.IDCLASSE
                WHERE
                    E.IDELEVE IS NOT NULL
                    AND C.IDCLASSE IS NULL
                    AND I.IDINSTRUMENT = :instrument;
            ");
            
            $req->bindParam(':instrument', $instrument, PDO::PARAM_INT);     
            $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Eleve');
            $req->execute();
            $resultats = $req->fetchAll();
            
            return $resultats;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des élèves sans classe par instrument : " . $e->getMessage());
        }
    }

    public static function ajoutEleve($idUtilisateur){
        try {
            $pdo = MonPdo::getInstance();
            $req = $pdo->prepare("INSERT INTO ELEVE (IDUTILISATEUR) VALUES (:idutilisateur);");
            $req->bindParam(':idutilisateur', $idUtilisateur, PDO::PARAM_STR);
            $req->execute();

        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'ajout élève " );
        }
    }
    public static function deleteEleve($idUtilisateur){
        try {
            $pdo = MonPdo::getInstance();
            $req = $pdo->prepare("DELETE FROM ELEVE WHERE IDUTILISATEUR = :idutilisateur;");
            $req->bindParam(':idutilisateur', $idUtilisateur, PDO::PARAM_INT);
            $req->execute();

        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'ajout professeur' " );
        }
    }
    public static function fromUtilisateur(Utilisateur $utilisateur, $ideleve) {
        return new self(
            $ideleve,
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
     * Get the value of IDELEVE
     */
    public function getIDELEVE()
    {
        return $this->IDELEVE;
    }

    /**
     * Set the value of IDELEVE
     *
     * @return  self
     */
    public function setIDELEVE($IDELEVE)
    {
        $this->IDELEVE = $IDELEVE;

        return $this;
    }
}

?>