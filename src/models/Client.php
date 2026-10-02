<?php
require_once __DIR__ . '/../models/Utilisateur.php';

class Client extends Utilisateur
{
    private $adrMail;
    private $prenom;
    private $nom;

    function __construct(PDO $pdo, $adrMail = null, $prenom = null, $nom = null, $idUtilisateur = null, $mdp = null, $statut = null, $token = null, $tokenExpire = null, $dateCreation = null)
    {
        parent::__construct($pdo, $idUtilisateur, $mdp, $statut, $token, $tokenExpire, $dateCreation, 'client');
        $this->adrMail = $adrMail;
        $this->prenom = $prenom;
        $this->nom = $nom;
    }

    // GETTERS
    public function GetAdrMail()
    {
        return $this->adrMail;
    }
    public function GetPrenom()
    {
        return $this->prenom;
    }
    public function GetNom()
    {
        return $this->nom;
    }
    public function GetAdrMailMasque()
    {
        $adrMail = $this->adrMail;
        $positionArobase = mb_strpos($adrMail, '@');

        if ($positionArobase === false) {
            return str_repeat('*', mb_strlen($adrMail));
        }

        $partieLocale = mb_substr($adrMail, 0, $positionArobase);
        $domaine = mb_substr($adrMail, $positionArobase);

        return mb_substr($partieLocale, 0, 1) . str_repeat('*', max(mb_strlen($partieLocale) - 1, 1)) . $domaine;
    }

    // SETTERS
    public function SetAdrMail($adrMail)
    {
        $this->adrMail = $adrMail;
    }
    public function SetPrenom($prenom)
    {
        $this->prenom = $prenom;
    }
    public function SetNom($nom)
    {
        $this->nom = $nom;
    }

    // CREATE
    public function CreationClient()
    {
        try {
            $this->pdo->beginTransaction();

            if (!parent::CreationUtilisateur()) {
                $this->pdo->rollBack();
                return false;
            }

            $req = "INSERT INTO Client (id_utilisateur, adr_mail, prenom, nom) VALUES (:id_utilisateur, :adr_mail, :prenom, :nom)";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindParam(':id_utilisateur', $this->idUtilisateur);
            $stmt->bindParam(':adr_mail', $this->adrMail);
            $stmt->bindParam(':prenom', $this->prenom);
            $stmt->bindParam(':nom', $this->nom);
            $stmt->execute();

            $this->pdo->commit();
            return true;
        } catch (PDOException) {
            $this->pdo->rollBack();
            return false;
        }
    }

    // READ
    // Récupère un client à partir de son adresse mail (page de connexion)
    public function RechercheClient($adrMail)
    {
        try {
            $req = "SELECT id_utilisateur, adr_mail, prenom, nom FROM Client WHERE adr_mail = :adr_mail";

            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':adr_mail', $adrMail);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return false;
            }

            $this->adrMail = $row['adr_mail'];
            $this->prenom = $row['prenom'];
            $this->nom = $row['nom'];

            return parent::RechercheUtilisateur($row['id_utilisateur']);
        } catch (PDOException) {
            return false;
        }
    }

    // Récupère un client à partir de son id (page de confirmation, renvoi de code)
    public function RechercheClientParId($idUtilisateur)
    {
        try {
            $req = "SELECT id_utilisateur, adr_mail, prenom, nom FROM Client WHERE id_utilisateur = :id_utilisateur";

            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':id_utilisateur', $idUtilisateur);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return false;
            }

            $this->adrMail = $row['adr_mail'];
            $this->prenom = $row['prenom'];
            $this->nom = $row['nom'];

            return parent::RechercheUtilisateur($row['id_utilisateur']);
        } catch (PDOException) {
            return false;
        }
    }

    // UPDATE
    public function MajClient($idUtilisateur)
    {
        try {
            $this->pdo->beginTransaction();

            if (!parent::MajUtilisateur($idUtilisateur)) {
                $this->pdo->rollBack();
                return false;
            }

            $req = "UPDATE Client SET adr_mail = :adr_mail, prenom = :prenom, nom = :nom WHERE id_utilisateur = :id_utilisateur";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':id_utilisateur', $idUtilisateur);
            $stmt->bindParam(':adr_mail', $this->adrMail);
            $stmt->bindParam(':prenom', $this->prenom);
            $stmt->bindParam(':nom', $this->nom);
            $stmt->execute();

            $this->pdo->commit();
            return true;
        } catch (PDOException) {
            $this->pdo->rollBack();
            return false;
        }
    }

    // DELETE
    public function SuppClient($idUtilisateur)
    {
        try {
            $this->pdo->beginTransaction();

            $req = "DELETE FROM Client WHERE id_utilisateur = :id_utilisateur";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':id_utilisateur', $idUtilisateur);
            $stmt->execute();

            if (!parent::SuppUtilisateur($idUtilisateur)) {
                $this->pdo->rollBack();
                return false;
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException) {
            $this->pdo->rollBack();
            return false;
        }
    }
}
