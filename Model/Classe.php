<?php

class Classe {
    private $IDCLASSE;
    private $IDINSTRUMENT;
    private $nomInstrument;
    private $eleves;

    public function __construct($IDCLASSE, $IDINSTRUMENT, $nomInstrument, $eleves = []) {
        $this->IDCLASSE = $IDCLASSE;
        $this->IDINSTRUMENT = $IDINSTRUMENT;
        $this->nomInstrument = $nomInstrument;
        $this->eleves = $eleves;
    }


    public static function getAll() {
        try {
            $pdo = MonPdo::getInstance();
            $req = $pdo->prepare("
                SELECT 
                    C.IDCLASSE, 
                    C.IDINSTRUMENT, 
                    I.LIBELLE as NOMINSTRUMENT,
                    U.IDUTILISATEUR,
                    U.NOM, 
                    U.PRENOM, 
                    U.TELEPHONE, 
                    U.ADRESSE, 
                    U.MAIL,
                    E.IDELEVE
                FROM 
                    CLASSE C
                LEFT JOIN 
                    CLASSE_ELEVE CE ON C.IDCLASSE = CE.IDCLASSE
                LEFT JOIN 
                    ELEVE E ON CE.IDELEVE = E.IDELEVE
                LEFT JOIN 
                    UTILISATEUR U ON E.IDUTILISATEUR = U.IDUTILISATEUR
                LEFT JOIN
                    INSTRUMENT I ON C.IDINSTRUMENT = I.IDINSTRUMENT
            ");
            
            $req->execute();
            $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    
            $classes = [];
            foreach ($resultats as $row) {
                $idClasse = $row['IDCLASSE'];
                if (!isset($classes[$idClasse])) {
                    $classes[$idClasse] = new Classe(
                        $row['IDCLASSE'],
                        $row['IDINSTRUMENT'],
                        $row['NOMINSTRUMENT']
                    );
                }
                if ($row['IDUTILISATEUR'] !== null) {
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
                    $classes[$idClasse]->eleves[] = $eleve;
                }
            }
    
            return array_values($classes);
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
    

            $req->execute();
            $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    

            $classes = [];
            foreach ($resultats as $row) {
                $classe = new Classe(
                    $row['IDCLASSE'],
                    '',
                    '',
                    '' 
                );
                $classes[] = $classe;
            }
    
            return $classes;
    
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des classes disponibles : " . $e->getMessage());
            return [];
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
            throw new Exception("Erreur lors de la récupération des élèves dans la classe : " . $e->getMessage());
        }
    }

    public static function ajouterClasseAvecEleves($listeEleves, $idInstrument) {
        $pdo = MonPdo::getInstance();
    
        try {

            $pdo->beginTransaction();
    

            $reqClasse = $pdo->prepare("INSERT INTO CLASSE (IDINSTRUMENT) VALUES (:idInstrument)");
            $reqClasse->bindParam(':idInstrument', $idInstrument, PDO::PARAM_INT);
            $reqClasse->execute();
            $idClasse = $pdo->lastInsertId();
    
            $reqAjoutEleve = $pdo->prepare("INSERT INTO CLASSE_ELEVE (IDCLASSE, IDELEVE) VALUES (:idClasse, :idEleve)");
    
            foreach ($listeEleves as $eleve) {

                $reqAjoutEleve->bindParam(':idClasse', $idClasse, PDO::PARAM_INT);
                $reqAjoutEleve->bindParam(':idEleve', $eleve, PDO::PARAM_INT);
                $reqAjoutEleve->execute();
            }
    

            $pdo->commit();
    
            echo "Classe créée avec succès et élèves ajoutés.";
        } catch (PDOException $e) {

            $pdo->rollBack();
            throw new Exception("Erreur lors de l'ajout des élèves à la classe : " . $e->getMessage());
        }
    }
    
    public static function modifierUneClasse($idClasse, $listeEleves) {
        $pdo = MonPdo::getInstance();
    
        try {

            $pdo->beginTransaction();
    

            $reqSuppression = $pdo->prepare("DELETE FROM CLASSE_ELEVE WHERE IDCLASSE = :idClasse");
            $reqSuppression->bindParam(':idClasse', $idClasse, PDO::PARAM_INT);
            $reqSuppression->execute();
    

            $reqAjoutEleve = $pdo->prepare("INSERT INTO CLASSE_ELEVE (IDCLASSE, IDELEVE) VALUES (:idClasse, :idEleve)");
    

            foreach ($listeEleves as $eleve) {
                $reqAjoutEleve->bindParam(':idClasse', $idClasse, PDO::PARAM_INT);
                $reqAjoutEleve->bindParam(':idEleve', $eleve, PDO::PARAM_INT);
                $reqAjoutEleve->execute();
            }
    

            $pdo->commit();
    
            echo "Modification de la classe réussie.";
        } catch (PDOException $e) {

            $pdo->rollBack();
            throw new Exception("Erreur lors de la modification de la classe : " . $e->getMessage());
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
     * Get the value of nomInstrument
     */ 
    public function getNomInstrument()
    {
        return $this->nomInstrument;
    }

    /**
     * Set the value of nomInstrument
     *
     * @return  self
     */ 
    public function setNomInstrument($nomInstrument)
    {
        $this->nomInstrument = $nomInstrument;

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
}

?>
