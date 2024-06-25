<?php

class Utilisateur
{

    private $IDUTILISATEUR;
    private $NOM;
    private $PRENOM;
    private $TELEPHONE;
    private $MAIL;
    private $INSTRUMENT;
    private $ADRESSE;
    private $MDP;
    private $EST_ADMIN;

    public function __construct($nom = null, $prenom = null, $telephone = null, $mail = null, $adresse = null, $mdp = null, $est_admin = null, $instruments = null, $idutilisateur = null){
        
        $this->NOM = $nom;
        $this->PRENOM = $prenom;
        $this->TELEPHONE = $telephone;
        $this->MAIL = $mail;
        $this->ADRESSE = $adresse;
        $this->MDP = $mdp;
        $this->EST_ADMIN = $est_admin == 1 ? 1 : 0;
        $this->INSTRUMENT = $instruments;
        $this->IDUTILISATEUR = $idutilisateur; 
    }

    public static function deconnexion(){
        unset($_SESSION['user']);
		unset($_SESSION['autorisation']);
    }

    public static function tentativeConnexion($login, $mdp) {
        try {

            $req = MonPdo::getInstance()->prepare("SELECT * FROM utilisateur WHERE mail = :mail");
            $req->bindParam(':mail', $login, PDO::PARAM_STR);
            $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Utilisateur');
            $req->execute();
            $user = $req->fetch();
        
            if ($user) {

                if (password_verify($mdp, $user->getMDP())) {
                    return $user; 
                } else {
                    return false; 
                }
            } else {
                return false;
            }
        
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la vérification de la connexion");
        }
    }
  
    public static function ajouterPersonne($utilisateur) {
        try {
            $pdo = MonPdo::getInstance();
            $pdo->beginTransaction();
            
            $req = $pdo->prepare("INSERT INTO UTILISATEUR (IDUTILISATEUR, NOM, PRENOM, TELEPHONE, ADRESSE, MAIL, MDP, EST_ADMIN)
                                  VALUES (:id, :nom, :prenom, :telephone, :adresse, :mail, :mdp, :est_admin)");
           
            $uid = $utilisateur->getIDUTILISATEUR();
            $nom = $utilisateur->getNom();
            $prenom = $utilisateur->getPrenom();
            $telephone = $utilisateur->getTelephone();
            $adresse = $utilisateur->getAdresse();
            $mail = $utilisateur->getMail();

            $mdp = password_hash($utilisateur->getMdp(), PASSWORD_BCRYPT);
            
            $est_admin = $utilisateur->getEST_ADMIN();
    
            $req->bindParam(':id', $uid);
            $req->bindParam(':nom', $nom);
            $req->bindParam(':prenom', $prenom);
            $req->bindParam(':telephone', $telephone);
            $req->bindParam(':adresse', $adresse);
            $req->bindParam(':mail', $mail);
            $req->bindParam(':mdp', $mdp);
            $req->bindParam(':est_admin', $est_admin);
            
            $req->execute(); 
            $pdo->commit();
            
            
            return $uid; 
            
        } catch (PDOException $e) {
            $pdo->rollback();
            throw new Exception("Erreur lors de l'ajout de l'utilisateur ");
        }
    }

    public static function supprimerUtilisateur($id_utilisateur) {
        try {
            $pdo = MonPdo::getInstance();
            $pdo->beginTransaction();

            $req = $pdo->prepare("DELETE FROM UTILISATEUR WHERE IDUTILISATEUR = :id_utilisateur");
            $req->bindParam(':id_utilisateur', $id_utilisateur);
            $req->execute();

            $pdo->commit();
            
        } catch (PDOException $e) {
            $pdo->rollback();
            throw new Exception("Erreur lors de la suppression de l'utilisateur : " . $e->getMessage());
        }
    }

    public static function modifierPersonne($utilisateur) {
        $pdo = MonPdo::getInstance();
        $pdo->beginTransaction();
    
        try {
            $req = $pdo->prepare("
                UPDATE UTILISATEUR
                SET NOM = :nom, PRENOM = :prenom, TELEPHONE = :telephone, ADRESSE = :adresse, MAIL = :mail, MDP = :mdp, EST_ADMIN = :est_admin
                WHERE IDUTILISATEUR = :id_utilisateur
            ");
    
            $id_utilisateur = $utilisateur->getIDUTILISATEUR();
            $nom = $utilisateur->getNOM();
            $prenom = $utilisateur->getPRENOM();
            $telephone = $utilisateur->getTELEPHONE();
            $adresse = $utilisateur->getADRESSE();
            $mail = $utilisateur->getMAIL();
            $mdp = $utilisateur->getMDP();
            $mdpHash = password_hash($mdp, PASSWORD_BCRYPT);
            $est_admin = $utilisateur->getEST_ADMIN();
            var_dump($mdp);
                var_dump($mdpHash);
    
            $req->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_STR);
            $req->bindParam(':nom', $nom, PDO::PARAM_STR);
            $req->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $req->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $req->bindParam(':adresse', $adresse, PDO::PARAM_STR);
            $req->bindParam(':mail', $mail, PDO::PARAM_STR);
            $req->bindParam(':mdp', $mdpHash, PDO::PARAM_STR);
            $req->bindParam(':est_admin', $est_admin, PDO::PARAM_BOOL);
    
            $req->execute();
    
            
            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollback();
            throw new Exception("Erreur lors de la modification de l'utilisateur : " . $e->getMessage());
        }
    }
    

    public static function getUtilisateur($id_utilisateur) {
        try {
            $pdo = MonPdo::getInstance();
            $req = $pdo->prepare("
                SELECT U.IDUTILISATEUR, U.NOM, U.PRENOM, U.TELEPHONE, U.ADRESSE, U.MAIL, U.MDP, U.EST_ADMIN,
                    E.IDELEVE, P.IDPROFESSEUR,
                    GROUP_CONCAT(I.IDINSTRUMENT SEPARATOR ', ') AS INSTRUMENTS
                FROM UTILISATEUR U
                LEFT JOIN ELEVE E ON U.IDUTILISATEUR = E.IDUTILISATEUR
                LEFT JOIN PROFESSEUR P ON U.IDUTILISATEUR = P.IDUTILISATEUR
                LEFT JOIN INSTRUMENT_UTILISATEUR IU ON U.IDUTILISATEUR = IU.IDUTILISATEUR
                LEFT JOIN INSTRUMENT I ON IU.IDINSTRUMENT = I.IDINSTRUMENT
                WHERE U.IDUTILISATEUR = :id_utilisateur
                GROUP BY U.IDUTILISATEUR, U.NOM, U.PRENOM, U.TELEPHONE, U.ADRESSE, U.MAIL, U.MDP, U.EST_ADMIN, E.IDELEVE, P.IDPROFESSEUR
            ");
            $req->bindParam(':id_utilisateur', $id_utilisateur);
            $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Utilisateur');
            $req->execute();
            $data = $req->fetch();

            return $data; 
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
        }
    }

    public static function recupererRole($id_utilisateur) {
        try {
            $pdo = MonPdo::getInstance();
            $req = $pdo->prepare("
                SELECT e.IDELEVE AS ID_ROLE, 'ELEVE' AS ROLE
                FROM ELEVE e
                WHERE e.IDUTILISATEUR = :id_utilisateur
                
                UNION ALL
                
                SELECT p.IDPROFESSEUR AS ID_ROLE, 'PROFESSEUR' AS ROLE
                FROM PROFESSEUR p
                WHERE p.IDUTILISATEUR = :id_utilisateur
            ");
            $req->bindValue(':id_utilisateur', $id_utilisateur, PDO::PARAM_STR);  
            $req->execute();

            $data = $req->fetch();  

            return $data;
        } catch (PDOException $e) {

            throw new Exception("Erreur lors de la récupération du rôle");
        }
    }
    
    public static function rechercheUtilisateur($utilisateur) {
        $utilisateur = "%" . $utilisateur . "%";
    
        try {
            $req = MonPdo::getInstance()->prepare("
                SELECT U.IDUTILISATEUR, U.NOM, U.PRENOM, U.TELEPHONE, U.ADRESSE, U.MAIL
                FROM UTILISATEUR U
                WHERE LOWER(U.NOM) LIKE :utilisateur
                OR LOWER(U.PRENOM) LIKE :utilisateur
            ");
            $req->bindParam(':utilisateur', $utilisateur, PDO::PARAM_STR);
            $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Utilisateur');
            $req->execute();
            $resultats = $req->fetchAll();

            return $resultats;
    
        } catch (PDOException $e) {
            echo "Erreur lors de la recherche des utilisateurs ";
        }
    }

    public static function getAll(){
        try {
            $pdo = MonPdo::getInstance();
            $req = $pdo->prepare("SELECT * FROM utilisateur");
            $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Utilisateur');
            $req->execute();
            $lesResultats = $req->fetchAll();

            return $lesResultats;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des utilisateurs " );
        }
    }

    public static function ajoutInstrumentUtilisateur($idUtilisateur, $idInstrument){
        
        try {
            $pdo = MonPdo::getInstance();
            $req = $pdo->prepare("INSERT INTO INSTRUMENT_UTILISATEUR (IDINSTRUMENT, IDUTILISATEUR) VALUES (:idinstrument, :idutilisateur);");
            $req->bindParam(':idutilisateur', $idUtilisateur, PDO::PARAM_STR);
            $req->bindParam(':idinstrument', $idInstrument, PDO::PARAM_INT);
            $req->execute();

        } catch (PDOException $e) {

            throw new Exception("Erreur lors de l'ajout d'instrument "  );
        }
    }
    public static function supprimerInstrumentUtilisateur($idUtilisateur){

        try {
            $pdo = MonPdo::getInstance();
            $reqDeleteInstruments = $pdo->prepare("DELETE FROM INSTRUMENT_UTILISATEUR WHERE IDUTILISATEUR = :id_utilisateur");
            $reqDeleteInstruments->bindParam(':id_utilisateur', $idUtilisateur, PDO::PARAM_STR);
            $reqDeleteInstruments->execute();

        } catch (PDOException $e) {
            echo($e->getMessage());
            throw new Exception("Erreur lors de l'ajout d'instrument "  );
        }
    }
    
    public static function checkClasseUtilisateur($idUtilisateur){

        try {
            $pdo = MonPdo::getInstance();
            $reqCheckClasse = $pdo->prepare("
                    SELECT c.IDINSTRUMENT
                    FROM CLASSE_ELEVE ce
                    JOIN CLASSE c ON ce.IDCLASSE = c.IDCLASSE
                    JOIN ELEVE e ON ce.IDELEVE = e.IDELEVE
                    WHERE e.IDUTILISATEUR = :id_utilisateur
                ");
            $reqCheckClasse->bindParam(':id_utilisateur', $idUtilisateur, PDO::PARAM_STR);
            $reqCheckClasse->execute();
            $classe = $reqCheckClasse->fetch(PDO::FETCH_ASSOC);
            return $classe;

        } catch (PDOException $e) {
            echo($e->getMessage());
            throw new Exception("Erreur lors de l'ajout d'instrument "  );
        }
    }
    public static function deleteClasseUtilisateur($idUtilisateur, $idInstrument){

        try {
            $pdo = MonPdo::getInstance();
            $reqDeleteClasse = $pdo->prepare("
                        DELETE ce
                        FROM CLASSE_ELEVE ce
                        JOIN CLASSE c ON ce.IDCLASSE = c.IDCLASSE
                        JOIN ELEVE e ON ce.IDELEVE = e.IDELEVE
                        WHERE e.IDUTILISATEUR = :id_utilisateur
                        AND c.IDINSTRUMENT != :id_instrument
                    ");
            $reqDeleteClasse->bindParam(':id_utilisateur', $idUtilisateur, PDO::PARAM_STR);
            $reqDeleteClasse->bindParam(':id_instrument', $idInstrument, PDO::PARAM_INT);
            $reqDeleteClasse->execute();

        } catch (PDOException $e) {
            echo($e->getMessage());
            throw new Exception("Erreur lors de l'ajout d'instrument "  );
        }
    }
    public static function deleteRoleUtilisateur($idUtilisateur){

        try {
            $pdo = MonPdo::getInstance();
            $reqDeleteRoles = $pdo->prepare("
                                DELETE FROM PROFESSEUR
                                WHERE IDUTILISATEUR = :id_utilisateur;
            
                                DELETE FROM ELEVE
                                WHERE IDUTILISATEUR = :id_utilisateur;
                            ");
            $reqDeleteRoles->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_STR);
            $reqDeleteRoles->execute();
            $reqDeleteRoles->closeCursor();

        } catch (PDOException $e) {
            echo($e->getMessage());
            throw new Exception("Erreur lors de l'ajout d'instrument "  );
        }
    }
    public static function modifierPersonneSansMdp($utilisateur) {
        $pdo = MonPdo::getInstance();
        $pdo->beginTransaction();
    
        try {
            $req = $pdo->prepare("
                UPDATE UTILISATEUR
                SET NOM = :nom, PRENOM = :prenom, TELEPHONE = :telephone, ADRESSE = :adresse, MAIL = :mail, EST_ADMIN = :est_admin
                WHERE IDUTILISATEUR = :id_utilisateur
            ");
    
            $id_utilisateur = $utilisateur->getIDUTILISATEUR();
            $nom = $utilisateur->getNOM();
            $prenom = $utilisateur->getPRENOM();
            $telephone = $utilisateur->getTELEPHONE();
            $adresse = $utilisateur->getADRESSE();
            $mail = $utilisateur->getMAIL();
            $est_admin = $utilisateur->getEST_ADMIN();
    
            $req->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_STR);
            $req->bindParam(':nom', $nom, PDO::PARAM_STR);
            $req->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $req->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $req->bindParam(':adresse', $adresse, PDO::PARAM_STR);
            $req->bindParam(':mail', $mail, PDO::PARAM_STR);
            $req->bindParam(':est_admin', $est_admin, PDO::PARAM_BOOL);
    
            $req->execute();
    
            
            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollback();
            throw new Exception("Erreur lors de la modification de l'utilisateur : " . $e->getMessage());
        }
    }
    
    
    /**
     * Get the value of IDUTILISATEUR
     */ 
    public function getIDUTILISATEUR()
    {
        return $this->IDUTILISATEUR;
    }

    /**
     * Set the value of IDUTILISATEUR
     *
     * @return  self
     */ 
    public function setIDUTILISATEUR($IDUTILISATEUR)
    {
        $this->IDUTILISATEUR = $IDUTILISATEUR;

        return $this;
    }

    /**
     * Get the value of NOM
     */ 
    public function getNOM()
    {
        return htmlspecialchars($this->NOM);
    }

    /**
     * Set the value of NOM
     *
     * @return  self
     */ 
    public function setNOM($NOM)
    {
        $this->NOM = $NOM;

        return $this;
    }

    /**
     * Get the value of PRENOM
     */ 
    public function getPRENOM()
    {
        return $this->PRENOM;
    }

    /**
     * Set the value of PRENOM
     *
     * @return  self
     */ 
    public function setPRENOM($PRENOM)
    {
        $this->PRENOM = $PRENOM;

        return $this;
    }

    /**
     * Get the value of TELEPHONE
     */ 
    public function getTELEPHONE()
    {
        return $this->TELEPHONE;
    }

    /**
     * Set the value of TELEPHONE
     *
     * @return  self
     */ 
    public function setTELEPHONE($TELEPHONE)
    {
        $this->TELEPHONE = $TELEPHONE;

        return $this;
    }

    /**
     * Get the value of MAIL
     */ 
    public function getMAIL()
    {
        return $this->MAIL;
    }

    /**
     * Set the value of MAIL
     *
     * @return  self
     */ 
    public function setMAIL($MAIL)
    {
        $this->MAIL = $MAIL;

        return $this;
    }

    /**
     * Get the value of INSTRUMENT
     */ 
    public function getINSTRUMENT()
    {
        return $this->INSTRUMENT;
    }

    /**
     * Set the value of INSTRUMENT
     *
     * @return  self
     */ 
    public function setINSTRUMENT($INSTRUMENT)
    {
        $this->INSTRUMENT = $INSTRUMENT;

        return $this;
    }

    /**
     * Get the value of ADRESSE
     */ 
    public function getADRESSE()
    {
        return $this->ADRESSE;
    }

    /**
     * Set the value of ADRESSE
     *
     * @return  self
     */ 
    public function setADRESSE($ADRESSE)
    {
        $this->ADRESSE = $ADRESSE;

        return $this;
    }

    /**
     * Get the value of MDP
     */ 
    public function getMDP()
    {
        return $this->MDP;
    }

    /**
     * Set the value of MDP
     *
     * @return  self
     */ 
    public function setMDP($MDP)
    {
        $this->MDP = $MDP;

        return $this;
    }

    /**
     * Get the value of EST_ADMIN
     */ 
    public function getEST_ADMIN()
    {
        return $this->EST_ADMIN;
    }

    /**
     * Set the value of EST_ADMIN
     *
     * @return  self
     */ 
    public function setEST_ADMIN($EST_ADMIN)
    {
        $this->EST_ADMIN = $EST_ADMIN;

        return $this;
    }
}
?>
