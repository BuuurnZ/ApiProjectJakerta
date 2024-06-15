<?php

class Eleve extends Utilisateur
{
    private $IDELEVE;

    public function __construct($ideleve, $nom, $prenom, $telephone, $mail, $adresse, $mdp, $est_admin, $instruments = [], $idutilisateur)
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
            $req->execute();
            $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
            
            $eleves = [];
            foreach ($resultats as $row) {
                $eleve = new Eleve(
                    $row['IDELEVE'],
                    $row['NOM'],
                    $row['PRENOM'],
                    $row['TELEPHONE'],
                    $row['MAIL'],
                    $row['ADRESSE'],
                    null,
                    false,
                    [],
                    $row['IDUTILISATEUR']
                );
                $eleves[] = $eleve;
            }
            
            return $eleves;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des élèves sans classe par instrument : " . $e->getMessage());
        }
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