<?php

/**
 * Summary of Seance
 */
class Seance
{
    private $idSeance;
	private $date;
	private $heureDebut;
    private $heureFin;
    private $idProfesseur;
	private $nomProfesseur;
	private $prenomProfesseur;
    private $idClasse;
	private $instrument;
    
	
    public static function getAll() {
        try {
            $req = MonPdo::getInstance()->prepare("
                SELECT 
                    S.idSeance, 
                    S.date, 
                    S.heureDebut, 
                    S.heureFin, 
                    P.idProfesseur, 
                    U.NOM AS nomProfesseur, 
                    U.PRENOM AS prenomProfesseur, 
                    C.idClasse, 
                    I.LIBELLE AS instrument
                FROM SEANCE S
                JOIN PROFESSEUR P ON S.IDPROFESSEUR = P.IDPROFESSEUR
                JOIN UTILISATEUR U ON P.IDUTILISATEUR = U.IDUTILISATEUR
                JOIN CLASSE C ON S.IDCLASSE = C.IDCLASSE
                JOIN INSTRUMENT I ON C.IDINSTRUMENT = I.IDINSTRUMENT
                ORDER BY S.DATE, S.HEUREDEBUT;
            ");
            $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'seance');
            $req->execute();
            $lesResultats = $req->fetchAll();
            return $lesResultats;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des séances : " . $e->getMessage());
        }
    }

    public static function ajouterSeance($idProfesseur, $idClasse, $datetime) {
        $pdo = MonPdo::getInstance();
    
        try {

            $date = substr($datetime, 0, 10);
            $heureDebut = substr($datetime, 11, 5);
            $heureFin = date('H:i', strtotime($heureDebut . ' + 2 hours'));
    

            $pdo->beginTransaction();
    
            $reqInsertSeance = $pdo->prepare("
                INSERT INTO SEANCE (IDPROFESSEUR, IDCLASSE, DATE, HEUREDEBUT, HEUREFIN)
                VALUES (:idProfesseur, :idClasse, :date, :heureDebut, :heureFin)
            ");
            $reqInsertSeance->bindParam(':idProfesseur', $idProfesseur, PDO::PARAM_STR);
            $reqInsertSeance->bindParam(':idClasse', $idClasse, PDO::PARAM_INT);
            $reqInsertSeance->bindParam(':date', $date,);
            $reqInsertSeance->bindParam(':heureDebut', $heureDebut);
            $reqInsertSeance->bindParam(':heureFin', $heureFin);
            $reqInsertSeance->execute();
    

            $pdo->commit();
    

            return true;
    
        } catch (PDOException $e) {

            $pdo->rollBack();
            echo "Erreur lors de l'ajout de la séance : " . $e->getMessage();
            return false;
        }
    }

    public static function supprimerSeance($idSeance) {
        try {

            $pdo = MonPdo::getInstance();
            $pdo->beginTransaction();

            $req = $pdo->prepare("DELETE FROM SEANCE WHERE IDSEANCE = :idSeance");
            $req->bindParam(':idSeance', $idSeance);
            $req->execute();
            $pdo->commit();

        } catch (PDOException $e) {
            $pdo->rollback();
            throw new Exception("Erreur lors de la suppression de l'utilisateur : " . $e->getMessage());
        }
    }

    public static function afficherClasseSeance($idSeance) {
        try {
            $pdo = MonPdo::getInstance();
            $pdo->beginTransaction();
    

            $req = $pdo->prepare("
                SELECT U.IDUTILISATEUR, U.NOM, U.PRENOM, U.TELEPHONE, U.MAIL, U.ADRESSE, E.IDELEVE
                FROM CLASSE_ELEVE CE
                JOIN ELEVE E ON CE.IDELEVE = E.IDELEVE
                JOIN UTILISATEUR U ON E.IDUTILISATEUR = U.IDUTILISATEUR
                JOIN SEANCE S ON CE.IDCLASSE = S.IDCLASSE
                WHERE S.IDSEANCE = :idSeance
            ");
            $req->bindParam(':idSeance', $idSeance);
            $req->execute();
            $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Utilisateur');
            $eleveData = $req->fetchAll();
    
            return $eleveData;
    
        } catch (PDOException $e) {
            $pdo->rollback();
            throw new Exception("Erreur lors de la récupération des élèves : " . $e->getMessage());
        }
    }

    public static function verifInstrumentProfEtClasse($idClasse, $idProfesseur){

        try {
            $pdo = MonPdo::getInstance();
            $reqVerifInstrument = $pdo->prepare("
            SELECT COUNT(*) AS nbInstruments
            FROM PROFESSEUR P
            JOIN UTILISATEUR U ON P.IDUTILISATEUR = U.IDUTILISATEUR
            JOIN INSTRUMENT_UTILISATEUR IU ON U.IDUTILISATEUR = IU.IDUTILISATEUR
            JOIN CLASSE C ON IU.IDINSTRUMENT = C.IDINSTRUMENT
            WHERE P.IDPROFESSEUR = :idProfesseur
            AND C.IDCLASSE = :idClasse
        ");

        $reqVerifInstrument->bindParam(':idProfesseur', $idProfesseur, PDO::PARAM_STR);
        $reqVerifInstrument->bindParam(':idClasse', $idClasse, PDO::PARAM_INT);
        $reqVerifInstrument->execute();
        $resultatVerifInstrument = $reqVerifInstrument->fetch(PDO::FETCH_ASSOC);

        return $resultatVerifInstrument;

        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la vérification d'instrument" );
        }
    }

    public static function verifConflitProfEtClasse($idClasse, $date, $heureDebut, $heureFin){
        try {
            $pdo = MonPdo::getInstance();
            $reqVerifConflit = $pdo->prepare("
                SELECT COUNT(*) AS nbConflits
                FROM SEANCE S
                WHERE S.IDCLASSE = :idClasse
                AND S.DATE = :date
                AND (
                    (:heureDebut BETWEEN S.HEUREDEBUT AND S.HEUREFIN)
                    OR (:heureFin BETWEEN S.HEUREDEBUT AND S.HEUREFIN)
                )
            ");
            $reqVerifConflit->bindParam(':idClasse', $idClasse, PDO::PARAM_INT);
            $reqVerifConflit->bindParam(':date', $date);
            $reqVerifConflit->bindParam(':heureDebut', $heureDebut);
            $reqVerifConflit->bindParam(':heureFin', $heureFin);
            $reqVerifConflit->execute();
            $resultatVerifConflit = $reqVerifConflit->fetch(PDO::FETCH_ASSOC);

        return $resultatVerifConflit;

        } catch (PDOException $e) {
            throw new Exception("Erreur vérification conflit classe et professeur" );
        }
    }
    

    /**
     * Get the value of idProfesseur
     */ 
    public function getIdProfesseur()
    {
        return $this->idProfesseur;
    }

    /**
     * Set the value of idProfesseur
     *
     * @return  self
     */ 
    public function setIdProfesseur($idProfesseur)
    {
        $this->idProfesseur = $idProfesseur;

        return $this;
    }

    /**
     * Get the value of idClasse
     */ 
    public function getIdClasse()
    {
        return $this->idClasse;
    }

    /**
     * Set the value of idClasse
     *
     * @return  self
     */ 
    public function setIdClasse($idClasse)
    {
        $this->idClasse = $idClasse;

        return $this;
    }

    /**
     * Get the value of date
     */ 
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */ 
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of heureDebut
     */ 
    public function getHeureDebut()
    {
        return $this->heureDebut;
    }

    /**
     * Set the value of heureDebut
     *
     * @return  self
     */ 
    public function setHeureDebut($heureDebut)
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

    /**
     * Get the value of heureFin
     */ 
    public function getHeureFin()
    {
        return $this->heureFin;
    }

    /**
     * Set the value of heureFin
     *
     * @return  self
     */ 
    public function setHeureFin($heureFin)
    {
        $this->heureFin = $heureFin;

        return $this;
    }

	/**
	 * Get the value of nomProfesseur
	 */ 
	public function getNomProfesseur()
	{
		return $this->nomProfesseur;
	}

	/**
	 * Set the value of nomProfesseur
	 *
	 * @return  self
	 */ 
	public function setNomProfesseur($nomProfesseur)
	{
		$this->nomProfesseur = $nomProfesseur;

		return $this;
	}

	/**
	 * Get the value of prenomProfesseur
	 */ 
	public function getPrenomProfesseur()
	{
		return $this->prenomProfesseur;
	}

	/**
	 * Set the value of prenomProfesseur
	 *
	 * @return  self
	 */ 
	public function setPrenomProfesseur($prenomProfesseur)
	{
		$this->prenomProfesseur = $prenomProfesseur;

		return $this;
	}

	/**
	 * Get the value of instrument
	 */ 
	public function getInstrument()
	{
		return $this->instrument;
	}

	/**
	 * Set the value of instrument
	 *
	 * @return  self
	 */ 
	public function setInstrument($instrument)
	{
		$this->instrument = $instrument;

		return $this;
	}

    /**
     * Get the value of idSeance
     */ 
    public function getIdSeance()
    {
        return $this->idSeance;
    }

    /**
     * Set the value of idSeance
     *
     * @return  self
     */ 
    public function setIdSeance($idSeance)
    {
        $this->idSeance = $idSeance;

        return $this;
    }
}
?>