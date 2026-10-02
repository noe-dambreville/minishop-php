<?php
require_once __DIR__ . '/../models/Utilisateur.php';

class Administrateur extends Utilisateur
{
    private $identifiant;
    private $niveauAcces;

    function __construct(PDO $pdo, $identifiant = null, $niveauAcces = null, $idUtilisateur = null, $mdp = null, $statut = null, $token = null, $tokenExpire = null, $dateCreation = null)
    {
        parent::__construct($pdo, $idUtilisateur, $mdp, $statut, $token, $tokenExpire, $dateCreation, 'admin');
        $this->identifiant = $identifiant;
        $this->niveauAcces = $niveauAcces;
    }

    // GETTERS
    public function GetIdentifiant()
    {
        return $this->identifiant;
    }
    public function GetNiveauAcces()
    {
        return $this->niveauAcces;
    }

    // SETTERS
    public function SetIdentifiant($identifiant)
    {
        $this->identifiant = $identifiant;
    }
    public function SetNiveauAcces($niveauAcces)
    {
        $this->niveauAcces = $niveauAcces;
    }

    // CRUD //

    // READ
    public function RechercheAdmin($identifiant)
    {
        try {
            $req = "SELECT id_utilisateur, identifiant, niveau_acces FROM Administrateur WHERE identifiant = :identifiant";

            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':identifiant', $identifiant);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return false;
            }

            $this->identifiant = $row['identifiant'];
            $this->niveauAcces = $row['niveau_acces'];

            return parent::RechercheUtilisateur($row['id_utilisateur']);
        } catch (PDOException) {
            return false;
        }
    }

    // UPDATE

    public function MajAdmin($idUtilisateur)
    {
        try {
            $this->pdo->beginTransaction();

            if (!parent::MajUtilisateur($idUtilisateur)) {
                $this->pdo->rollBack();
                return false;
            }

            $req = "UPDATE Administrateur SET identifiant = :identifiant, niveau_acces = :niveau_acces WHERE id_utilisateur = :id_utilisateur";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':id_utilisateur', $idUtilisateur);
            $stmt->bindParam(':identifiant', $this->identifiant);
            $stmt->bindParam(':niveau_acces', $this->niveauAcces);
            $stmt->execute();

            $this->pdo->commit();
            return true;
            
        } catch(PDOException){
            $this->pdo->rollBack();
            return false;
        }
    }
}