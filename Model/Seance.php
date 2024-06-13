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