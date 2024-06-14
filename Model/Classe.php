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

    // Méthode pour récupérer toutes les classes avec leurs élèves
    public static function getAll() {
        $pdo = MonPdo::getInstance();

        // Requête SQL pour récupérer les classes avec leurs élèves
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
                    null, // MDP
                    false, // EST_ADMIN
                    [], // INSTRUMENTS
                    $row['IDUTILISATEUR']
                );
                $classes[$idClasse]->eleves[] = $eleve;
            }
        }

        return array_values($classes);
    }

    public static function supprimerClasse($idClasse) {
        $pdo = MonPdo::getInstance();
    
        try {
            // Démarre une transaction
            $pdo->beginTransaction();
    
            // Suppression des élèves associés à la classe
            $reqSuppressionEleves = $pdo->prepare("
                DELETE FROM CLASSE_ELEVE WHERE IDCLASSE = :idClasse
            ");
            $reqSuppressionEleves->bindParam(':idClasse', $idClasse, PDO::PARAM_INT);
            $reqSuppressionEleves->execute();
    
            // Suppression de la classe elle-même
            $reqSuppressionClasse = $pdo->prepare("
                DELETE FROM CLASSE WHERE IDCLASSE = :idClasse
            ");
            $reqSuppressionClasse->bindParam(':idClasse', $idClasse, PDO::PARAM_INT);
            $reqSuppressionClasse->execute();
    
            // Valide la transaction
            $pdo->commit();
    
            // Retourne vrai si la suppression s'est bien passée
            return true;
    
        } catch (PDOException $e) {
            // En cas d'erreur, annule la transaction et affiche l'erreur
            $pdo->rollBack();
            echo "Erreur lors de la suppression de la classe : " . $e->getMessage();
            return false;
        }
    }
    
    public static function getClassesDisponibles($idInstrument, $datetime) {
        $pdo = MonPdo::getInstance();
    
        try {
            // Extraire la date et l'heure de la chaîne datetime
            $date = substr($datetime, 0, 10);
            $heureDebut = substr($datetime, 11, 5);
            $heureFin = date('H:i', strtotime($heureDebut . ' + 2 hours'));
    
            // Requête pour récupérer les classes disponibles
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
    
            // Liaison des paramètres pour la requête des classes
            $req->bindParam(':idInstrument', $idInstrument, PDO::PARAM_INT);
            $req->bindParam(':date', $date);
            $req->bindParam(':heureDebut', $heureDebut);
            $req->bindParam(':heureFin', $heureFin);
    
            // Exécution de la requête des classes
            $req->execute();
            $resultats = $req->fetchAll(PDO::FETCH_ASSOC);
    
            // Construction des objets Classe à partir des résultats
            $classes = [];
            foreach ($resultats as $row) {
                $classe = new Classe(
                    $row['IDCLASSE'],
                    '',
                    '',
                    '' // Nom de l'instrument associé à la classe
                );
                $classes[] = $classe;
            }
    
            // Retourner la liste des classes disponibles
            return $classes;
    
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des classes disponibles : " . $e->getMessage();
            return [];
        }
    }
    
    public static function getElevesDansClasse($idClasse) {
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
                $row['IDELEVE'], // IDELEVE
                $row['NOM'],
                $row['PRENOM'],
                $row['TELEPHONE'],
                $row['MAIL'],
                $row['ADRESSE'],
                null, // MDP
                false, // EST_ADMIN
                [], // INSTRUMENTS
                $row['IDUTILISATEUR']
            );
            $eleves[] = $eleve;
        }

        return $eleves;
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
            // En cas d'erreur, annuler la transaction
            $pdo->rollBack();
            echo "Erreur lors de l'ajout des élèves à la classe : " . $e->getMessage();
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
