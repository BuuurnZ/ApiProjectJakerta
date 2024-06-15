<?php

class Utilisateur
{

    private $idutilisateur;
    private $nom;
    private $prenom;
    private $telephone;
    private $mail;
    private $instruments;
    private $adresse;
    private $mdp;
    private $est_admin;

    public function __construct($nom, $prenom, $telephone, $mail, $adresse, $mdp, $est_admin, $instruments = [], $idutilisateur){
        
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->telephone = $telephone;
        $this->mail = $mail;
        $this->adresse = $adresse;
        $this->mdp = $mdp;
        $this->est_admin = $est_admin == 1 ? 1 : 0;
        $this->instruments = $instruments;
        $this->idutilisateur = $idutilisateur; 
    }

    public static function getAll(){
        try {
            $pdo = MonPdo::getInstance();
            $req = $pdo->prepare("SELECT * FROM utilisateur");
            $req->execute();
            $lesResultats = $req->fetchAll(PDO::FETCH_ASSOC);
            $utilisateurs = [];
            foreach ($lesResultats as $resultat) {

                $utilisateur = new Utilisateur(
                    $resultat['NOM'],
                    $resultat['PRENOM'],
                    $resultat['TELEPHONE'],
                    $resultat['MAIL'],
                    $resultat['ADRESSE'],
                    $resultat['MDP'],
                    $resultat['EST_ADMIN'],
                    [],
                    $resultat['IDUTILISATEUR']
                );
                $utilisateurs[] = $utilisateur;
            }

            return $utilisateurs;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
        }
    }

    public static function checkConnexion($login, $pw){
        try {
            $req = MonPdo::getInstance()->prepare("SELECT COUNT(*) FROM utilisateur WHERE mail = :login AND mdp = :pw");
            $req->bindParam(':login', $login);
            $req->bindParam(':pw', $pw);
            $req->execute();
            $nb_lignes = $req->fetchColumn();

            return $nb_lignes > 0;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la vérification de la connexion : " . $e->getMessage());
        }
    }
  
    public static function ajouterPersonne($utilisateur, $role) {
        try {
            $pdo = MonPdo::getInstance();
            $pdo->beginTransaction();
            
            $req = $pdo->prepare("INSERT INTO UTILISATEUR (NOM, PRENOM, TELEPHONE, ADRESSE, MAIL, MDP, EST_ADMIN)
                                  VALUES (:nom, :prenom, :telephone, :adresse, :mail, :mdp, :est_admin)");
           
            $nom = $utilisateur->getNom();
            $prenom = $utilisateur->getPrenom();
            $telephone = $utilisateur->getTelephone();
            $adresse = $utilisateur->getAdresse();
            $mail = $utilisateur->getMail();
            $mdp = $utilisateur->getMdp();
            $est_admin = $utilisateur->getEstadmin();
    
            $req->bindParam(':nom', $nom);
            $req->bindParam(':prenom', $prenom);
            $req->bindParam(':telephone', $telephone);
            $req->bindParam(':adresse', $adresse);
            $req->bindParam(':mail', $mail);
            $req->bindParam(':mdp', $mdp);
            $req->bindParam(':est_admin', $est_admin);
    
            $req->execute();
            if($role != ""){
                $id_utilisateur = $pdo->lastInsertId();
                    
                $reqRole = $pdo->prepare("INSERT INTO $role (IDUTILISATEUR) VALUES (:id_utilisateur)");
                $reqRole->bindParam(':id_utilisateur', $id_utilisateur);
                $reqRole->execute();

                $instruments = $utilisateur->getInstruments();

                foreach ($instruments as $instrument) {
                    
                    $reqInstrument = $pdo->prepare("INSERT INTO INSTRUMENT_UTILISATEUR (IDINSTRUMENT, IDUTILISATEUR) VALUES (:id_instrument, :id_utilisateur)");
                    $reqInstrument->bindParam(':id_instrument', $instrument);
                    $reqInstrument->bindParam(':id_utilisateur', $id_utilisateur);
                    $reqInstrument->execute();
                }

                
                $pdo->commit();
            }
            else {
                
                $pdo->commit();
            }
            
            
        } catch (PDOException $e) {
            $pdo->rollback();
            throw new Exception("Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage());
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
            echo "Utilisateur supprimé avec succès.";
        } catch (PDOException $e) {
            $pdo->rollback();
            throw new Exception("Erreur lors de la suppression de l'utilisateur : " . $e->getMessage());
        }
    }

    public static function modifierPersonne($utilisateur, $role, $ancienRole) {
        $pdo = MonPdo::getInstance();
        $pdo->beginTransaction();
    
        try {
            $req = $pdo->prepare("
                UPDATE UTILISATEUR
                SET NOM = :nom, PRENOM = :prenom, TELEPHONE = :telephone, ADRESSE = :adresse, MAIL = :mail, MDP = :mdp, EST_ADMIN = :est_admin
                WHERE IDUTILISATEUR = :id_utilisateur
            ");
    
            $id_utilisateur = $utilisateur->getIdutilisateur();
            $nom = $utilisateur->getNom();
            $prenom = $utilisateur->getPrenom();
            $telephone = $utilisateur->getTelephone();
            $adresse = $utilisateur->getAdresse();
            $mail = $utilisateur->getMail();
            $mdp = $utilisateur->getMdp();
            $est_admin = $utilisateur->getEstadmin();
    
            $req->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
            $req->bindParam(':nom', $nom, PDO::PARAM_STR);
            $req->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $req->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $req->bindParam(':adresse', $adresse, PDO::PARAM_STR);
            $req->bindParam(':mail', $mail, PDO::PARAM_STR);
            $req->bindParam(':mdp', $mdp, PDO::PARAM_STR);
            $req->bindParam(':est_admin', $est_admin, PDO::PARAM_BOOL);
    
            $req->execute();
    
            $reqDeleteInstruments = $pdo->prepare("DELETE FROM INSTRUMENT_UTILISATEUR WHERE IDUTILISATEUR = :id_utilisateur");
            $reqDeleteInstruments->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
            $reqDeleteInstruments->execute();
    
            $instruments = $utilisateur->getInstruments();
            foreach ($instruments as $instrument) {
                $reqInstrument = $pdo->prepare("INSERT INTO INSTRUMENT_UTILISATEUR (IDINSTRUMENT, IDUTILISATEUR) VALUES (:id_instrument, :id_utilisateur)");
                $reqInstrument->bindParam(':id_instrument', $instrument, PDO::PARAM_INT);
                $reqInstrument->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
                $reqInstrument->execute();
            }
    
            if ($role != "") {
    
                if ($role == "ELEVE") {

                    if ($ancienRole == "PROFESSEUR") {
                        $reqDeleteProfesseur = $pdo->prepare("DELETE FROM PROFESSEUR WHERE IDUTILISATEUR = :id_utilisateur");
                        $reqDeleteProfesseur->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
                        $reqDeleteProfesseur->execute();
                    }
    

                    if ($ancienRole != $role) {
                        $reqEleve = $pdo->prepare("
                            INSERT INTO ELEVE (IDUTILISATEUR)
                            VALUES (:id_utilisateur)
                        ");
                        $reqEleve->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
                        $reqEleve->execute();
                    }
                }
    
                elseif ($role == "PROFESSEUR") {

                    if ($ancienRole == "ELEVE") {
                        $reqDeleteEleve = $pdo->prepare("DELETE FROM ELEVE WHERE IDUTILISATEUR = :id_utilisateur");
                        $reqDeleteEleve->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
                        $reqDeleteEleve->execute();
                    }
    

                    if ($ancienRole != $role) {
                        $reqProfesseur = $pdo->prepare("
                            INSERT INTO PROFESSEUR (IDUTILISATEUR)
                            VALUES (:id_utilisateur)
                        ");
                        $reqProfesseur->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
                        $reqProfesseur->execute();
                    }
                }
            }
            else {
                if ($ancienRole != "ADMIN") {
                    $reqDeleteRoles = $pdo->prepare("
                        DELETE FROM PROFESSEUR
                        WHERE IDUTILISATEUR = :id_utilisateur;
    
                        DELETE FROM ELEVE
                        WHERE IDUTILISATEUR = :id_utilisateur;
                    ");
                    $reqDeleteRoles->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
                    $reqDeleteRoles->execute();
                    $reqDeleteRoles->closeCursor();
                }
            }
    
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
            $req->execute();
            $data = $req->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                $instruments = explode(', ', $data['INSTRUMENTS']);

                if ($data['IDELEVE'] !== null) {
                    return new Eleve(
                        $data['IDELEVE'], $data['NOM'], $data['PRENOM'], $data['TELEPHONE'],
                        $data['MAIL'], $data['ADRESSE'], $data['MDP'], $data['EST_ADMIN'],
                        $instruments, $data['IDUTILISATEUR']
                    );
                } elseif ($data['IDPROFESSEUR'] !== null) {
                    return new Professeur(
                        $data['IDPROFESSEUR'], $data['NOM'], $data['PRENOM'], $data['TELEPHONE'],
                        $data['MAIL'], $data['ADRESSE'], $data['MDP'], $data['EST_ADMIN'],
                        $instruments, $data['IDUTILISATEUR'], $data['IDUTILISATEUR']
                    );
                } else {
                    return new Utilisateur(
                        $data['NOM'], $data['PRENOM'], $data['TELEPHONE'],
                        $data['MAIL'], $data['ADRESSE'], $data['MDP'], $data['EST_ADMIN'],
                        $instruments, $data['IDUTILISATEUR']
                    );
                }
            }

            return null; 
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
                
                UNION
                
                SELECT p.IDPROFESSEUR AS ID_ROLE, 'PROFESSEUR' AS ROLE
                FROM PROFESSEUR p
                WHERE p.IDUTILISATEUR = :id_utilisateur
            ");
            $req->bindValue(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
            $req->execute();
            $data = $req->fetch(PDO::FETCH_ASSOC);
            return $data ? $data : null;
        } catch (PDOException $e) {

            throw new Exception("Erreur lors de la récupération du rôle : " . $e->getMessage());
        }
    }
    
    public static function deconnexion(){
        unset($_SESSION['user']);
		unset($_SESSION['autorisation']);
    }

    /**
     * Get the value of nom
     */ 
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set the value of nom
     *
     * @return  self
     */ 
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get the value of prenom
     */ 
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set the value of prenom
     *
     * @return  self
     */ 
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get the value of mail
     */ 
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set the value of mail
     *
     * @return  self
     */ 
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get the value of adresse
     */ 
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set the value of adresse
     *
     * @return  self
     */ 
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get the value of idutilisateur
     */ 
    public function getIdutilisateur()
    {
        return $this->idutilisateur;
    }

    /**
     * Set the value of idutilisateur
     *
     * @return  self
     */ 
    public function setIdutilisateur($idutilisateur)
    {
        $this->idutilisateur = $idutilisateur;

        return $this;
    }

    /**
     * Get the value of telephone
     */ 
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set the value of telephone
     *
     * @return  self
     */ 
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get the value of mdp
     */ 
    public function getMdp()
    {
        return $this->mdp;
    }

    /**
     * Set the value of mdp
     *
     * @return  self
     */ 
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;

        return $this;
    }

    /**
     * Get the value of est_admin
     */ 
    public function getEstadmin()
    {
        return $this->est_admin;
    }

    /**
     * Set the value of est_admin
     *
     * @return  self
     */ 
    public function setEstadmin($est_admin)
    {
        $this->est_admin = $est_admin;

        return $this;
    }

    /**
     * Get the value of instrument
     */ 
    public function getInstruments()
    {
        return $this->instruments;
    }

    /**
     * Set the value of instrument
     *
     * @return  self
     */ 
    public function setInstruments($instrument)
    {
        $this->instruments = $instrument;

        return $this;
    }
}
?>
