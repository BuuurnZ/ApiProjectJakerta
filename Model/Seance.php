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
    
	
    public static function getAll(){
        $req = MonPdo::getInstance()->prepare("SELECT S.idSeance, S.date, S.heureDebut, S.heureFin, 
			P.idProfesseur, U.NOM AS nomProfesseur, U.PRENOM AS prenomProfesseur, 
			C.idClasse, I.LIBELLE AS instrument
			FROM SEANCE S
			JOIN PROFESSEUR P ON S.IDPROFESSEUR = P.IDPROFESSEUR
			JOIN UTILISATEUR U ON P.IDUTILISATEUR = U.IDUTILISATEUR
			JOIN CLASSE C ON S.IDCLASSE = C.IDCLASSE
			JOIN INSTRUMENT I ON C.IDINSTRUMENT = I.IDINSTRUMENT
			ORDER BY S.DATE, S.HEUREDEBUT;");
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'seance');
        $req->execute();
        $lesResultats = $req->fetchAll();
        return $lesResultats;
    }

    public static function ajouterSeance($idProfesseur, $idClasse, $datetime) {
        $pdo = MonPdo::getInstance();
    
        try {
            // Extraire la date et l'heure de la chaîne datetime
            $date = substr($datetime, 0, 10);
            $heureDebut = substr($datetime, 11, 5);
            $heureFin = date('H:i', strtotime($heureDebut . ' + 2 hours'));
    
            // Démarre une transaction
            $pdo->beginTransaction();
    
            // Vérifier que l'instrument du professeur et de la classe sont les mêmes
            $reqVerifInstrument = $pdo->prepare("
                SELECT COUNT(*) AS nbInstruments
                FROM PROFESSEUR P
                JOIN UTILISATEUR U ON P.IDUTILISATEUR = U.IDUTILISATEUR
                JOIN INSTRUMENT_UTILISATEUR IU ON U.IDUTILISATEUR = IU.IDUTILISATEUR
                JOIN CLASSE C ON IU.IDINSTRUMENT = C.IDINSTRUMENT
                WHERE P.IDPROFESSEUR = :idProfesseur
                AND C.IDCLASSE = :idClasse
            ");

            $reqVerifInstrument->bindParam(':idProfesseur', $idProfesseur, PDO::PARAM_INT);
            $reqVerifInstrument->bindParam(':idClasse', $idClasse, PDO::PARAM_INT);
            $reqVerifInstrument->execute();
            $resultatVerifInstrument = $reqVerifInstrument->fetch(PDO::FETCH_ASSOC);
    
            if ($resultatVerifInstrument['nbInstruments'] != 1) {
                throw new Exception("Le professeur et la classe doivent avoir le même instrument.");
            }
    
            // Vérifier les conflits de planning
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
    
            if ($resultatVerifConflit['nbConflits'] > 0) {
                throw new Exception("Il y a déjà un cours prévu pour cette classe à ce créneau horaire le même jour.");
            }
    
            // Insérer la nouvelle séance
            $reqInsertSeance = $pdo->prepare("
                INSERT INTO SEANCE (IDPROFESSEUR, IDCLASSE, DATE, HEUREDEBUT, HEUREFIN)
                VALUES (:idProfesseur, :idClasse, :date, :heureDebut, :heureFin)
            ");
            $reqInsertSeance->bindParam(':idProfesseur', $idProfesseur, PDO::PARAM_INT);
            $reqInsertSeance->bindParam(':idClasse', $idClasse, PDO::PARAM_INT);
            $reqInsertSeance->bindParam(':date', $date);
            $reqInsertSeance->bindParam(':heureDebut', $heureDebut);
            $reqInsertSeance->bindParam(':heureFin', $heureFin);
            $reqInsertSeance->execute();
    
            // Valide la transaction
            $pdo->commit();
    
            // Retourne vrai si l'insertion s'est bien passée
            return true;
    
        } catch (PDOException $e) {
            // En cas d'erreur, annule la transaction et affiche l'erreur
            $pdo->rollBack();
            echo "Erreur lors de l'ajout de la séance : " . $e->getMessage();
            return false;
        }
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
}
?>