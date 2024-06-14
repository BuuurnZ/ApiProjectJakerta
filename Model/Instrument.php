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