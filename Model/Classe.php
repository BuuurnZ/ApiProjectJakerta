<?php

class Classe {
    private $IDCLASSE;
    private $IDINSTRUMENT;
    private $NOMINSTRUMENT;
    private $eleves;

    public function __construct($IDCLASSE = NULL, $IDINSTRUMENT= NULL, $nomInstrument= NULL, $eleves = []) {
        $this->IDCLASSE = $IDCLASSE;
        $this->IDINSTRUMENT = $IDINSTRUMENT;
        $this->NOMINSTRUMENT = $nomInstrument;
        $this->eleves = $eleves;
    }


    public static function getAll() {
        try {
            $pdo = MonPdo::getInstance();
            $req = $pdo->prepare("
            SELECT 
                C.IDCLASSE, 
                C.IDINSTRUMENT, 
                I.LIBELLE as NOMINSTRUMENT
            FROM 
                CLASSE C
            LEFT JOIN
                INSTRUMENT I ON C.IDINSTRUMENT = I.IDINSTRUMENT
            ");
            
            $req->execute();
            $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Classe');
            $classes = $req->fetchAll();
    
    
            return $classes;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des classes et des élèves : " . $e->getMessage());
        }
    }

    public static function supprimerClasse($idClasse) {
        $pdo = MonPdo::getInstance();
    
        try {

            $pdo->beginTransaction();
    

            $reqSuppressionEleves = $pdo->prepare("
                DELETE FROM CLASSE_ELEVE WHERE IDCLASSE = :idClasse
            ");
            $reqSuppressionEleves->bindParam(':idClasse', $idClasse, PDO::PARAM_INT);
            $reqSuppressionEleves->execute();
    

            $reqSuppressionClasse = $pdo->prepare("
                DELETE FROM CLASSE WHERE IDCLASSE = :idClasse
            ");
            $reqSuppressionClasse->bindParam(':idClasse', $idClasse, PDO::PARAM_INT);
            $reqSuppressionClasse->execute();
    

            $pdo->commit();
    

            return true;
    
        } catch (PDOException $e) {

            $pdo->rollBack();
            throw new Exception("Erreur lors de la suppression de la classe : " . $e->getMessage());
            return false;
        }
    }
    
    public static function getClassesDisponibles($idInstrument, $datetime) {
        $pdo = MonPdo::getInstance();
    
        try {

            $date = substr($datetime, 0, 10);
            $heureDebut = substr($datetime, 11, 5);
            $heureFin = date('H:i', strtotime($heureDebut . ' + 2 hours'));
    

            $req = $pdo->prepare("
                SELECT DISTINCT C.IDCLASSE, I.LIBELLE AS LIBELLE_INSTRUMENT
                FROM CLASSE C
                JOIN INSTRUMENT I ON C.IDINSTRUMENT = I.IDINSTRUMENT
                LEFT JOIN SEANCE S ON C.IDCLASSE = S.IDCLASSE AND S.DATE = :date
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
            $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Classe');
            $req->execute();
            $classes = $req->fetchAll();

    
            return $classes;
    
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des classes disponibles : " . $e->getMessage());

        }
    }
    
    public static function getElevesDansClasse($idClasse) {
        try {
            $pdo = MonPdo::getInstance();
            $req = $pdo->prepare("
                SELECT U.IDUTILISATEUR, U.NOM, U.PRENOM, U.TELEPHONE, U.ADRESSE, U.MAIL, E.IDELEVE
                FROM UTILISATEUR U
                JOIN ELEVE E ON U.IDUTILISATEUR = E.IDUTILISATEUR
                JOIN CLASSE_ELEVE CE ON E.IDELEVE = CE.IDELEVE
                WHERE CE.IDCLASSE = :idClasse
            ");
            $req->bindParam(':idClasse', $idClasse, PDO::PARAM_INT);
            $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Utilisateur');
            $req->execute();
            $resultats = $req->fetchAll();
    
            return $resultats;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des élèves dans la classe : " . $e->getMessage());
        }
    }

    
    public static function supprimerClasseEleve($idClasse){

        try {
            $pdo = MonPdo::getInstance();
            
            $reqSuppression = $pdo->prepare("DELETE FROM CLASSE_ELEVE WHERE IDCLASSE = :idClasse");
            $reqSuppression->bindParam(':idClasse', $idClasse, PDO::PARAM_INT);
            $reqSuppression->execute();
    

        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la suppression de ClasseEleve "  );
        }
    }

    public static function ajoutClasseEleve($idClasse, $eleve){

        try {
            $pdo = MonPdo::getInstance();
            
            $reqAjoutEleve = $pdo->prepare("INSERT INTO CLASSE_ELEVE (IDCLASSE, IDELEVE) VALUES (:idClasse, :idEleve)");
            $reqAjoutEleve->bindParam(':idClasse', $idClasse, PDO::PARAM_INT);
            $reqAjoutEleve->bindParam(':idEleve', $eleve, PDO::PARAM_STR);
            $reqAjoutEleve->execute();
    

        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'ajout de l'élève "  );
        }
    }
    public static function ajoutClasse($idInstrument){

        try {
            $pdo = MonPdo::getInstance();
            
            $reqClasse = $pdo->prepare("INSERT INTO CLASSE (IDINSTRUMENT) VALUES (:idInstrument)");
            $reqClasse->bindParam(':idInstrument', $idInstrument, PDO::PARAM_INT);
            $reqClasse->execute();

            $idClasse = $pdo->lastInsertId();
    
            return $idClasse;

        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'ajout de l'élève "  );
        }
    }
    /**
     * Get the value of IDCLASSE
     */ 
    public function getIDCLASSE()
    {
        return $this->IDCLASSE;
    }

    /**
     * Set the value of IDCLASSE
     *
     * @return  self
     */ 
    public function setIDCLASSE($IDCLASSE)
    {
        $this->IDCLASSE = $IDCLASSE;

        return $this;
    }

    /**
     * Get the value of IDINSTRUMENT
     */ 
    public function getIDINSTRUMENT()
    {
        return $this->IDINSTRUMENT;
    }

    /**
     * Set the value of IDINSTRUMENT
     *
     * @return  self
     */ 
    public function setIDINSTRUMENT($IDINSTRUMENT)
    {
        $this->IDINSTRUMENT = $IDINSTRUMENT;

        return $this;
    }


    /**
     * Get the value of eleves
     */ 
    public function getEleves()
    {
        return $this->eleves;
    }

    /**
     * Set the value of eleves
     *
     * @return  self
     */ 

    public function setEleves($eleves)
    {
        $this->eleves = $eleves;

        return $this;
    }
    public function addEleve($eleve) {
        $this->eleves[] = $eleve;
    }


    /**
     * Get the value of NOMINSTRUMENT
     */ 
    public function getNOMINSTRUMENT()
    {
        return $this->NOMINSTRUMENT;
    }

    /**
     * Set the value of NOMINSTRUMENT
     *
     * @return  self
     */ 
    public function setNOMINSTRUMENT($NOMINSTRUMENT)
    {
        $this->NOMINSTRUMENT = $NOMINSTRUMENT;

        return $this;
    }
}

?>
