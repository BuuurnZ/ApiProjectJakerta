<?php

class Instrument
{

    private $IDINSTRUMENT;
    private $LIBELLE;
    

    public static function getAll(){
        $req = MonPdo::getInstance()->prepare("SELECT * FROM INSTRUMENT;");  
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Instrument');
        $req->execute();
        $lesResultats = $req->fetchAll(); 
        return $lesResultats;
    }

    public static function supprimerInstrument($instrument){

        $req = MonPdo::getInstance()->prepare("DELETE FROM `INSTRUMENT` WHERE IDINSTRUMENT = :instrument");  
        $req->bindParam(':instrument', $instrument, PDO::PARAM_INT);
        $req->execute();
    }

    public static function ajouterInstrument($instrument){

        $req = MonPdo::getInstance()->prepare("INSERT INTO `INSTRUMENT`(`LIBELLE`) VALUES (:instrument)");  
        $req->bindParam(':instrument', $instrument, PDO::PARAM_STR);
        $req->execute();
    }


    /**
     * Get the value of LIBELLE
     */ 
    public function getLIBELLE()
    {
        return $this->LIBELLE;
    }

    /**
     * Set the value of LIBELLE
     *
     * @return  self
     */ 
    public function setLIBELLE($LIBELLE)
    {
        $this->LIBELLE = $LIBELLE;

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
}
?>