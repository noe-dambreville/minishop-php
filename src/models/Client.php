<?php
require_once '../traits/Generateur.php';

class Client extends Utilisateur
{
    use Generateur;
    private $pdo;
    private $adrMail;
    private $prenom;
    private $nom;

    function __construct(PDO $pdo, $adrMail = null, $prenom = null, $nom = null, $idUtilisateur = null, $mdp = null, $estValide = null, $token = null, $tokenExpire = null, $dateCreation = null)
    {
        parent::__construct($idUtilisateur, $mdp, $estValide, $token, $tokenExpire, $dateCreation);
        $this->pdo = $pdo;
        $this->adrMail = $adrMail;
        $this->prenom = $prenom;
        $this->nom = $nom;
    }

    public function GetadrMail()
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

    public function SetadrMail($adrMail)
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
            $stmt->bindParam('id_utilisateur', $this->idUtilisateur);
            $stmt->bindParam('adr_mail', $this->adrMail);
            $stmt->bindParam('prenom', $this->prenom);
            $stmt->bindParam('nom', $this->nom);
            $stmt->execute();

            $this->pdo->commit();
            return true;

        } catch (PDOException) {
            $this->pdo->rollBack();
            return false;
        }
    }


    // Récupère un utilisateur client à partir de son adresse mail via la page de connexion
    public function RechercheClient($mail)
    {
        try {
            $req = "SELECT id_utilisateur, adr_mail, prenom, nom FROM Client
                WHERE adr_mail = :adrMail";

            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':adrMail', $mail);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->adrMail = $row['adr_mail'];
            $this->prenom = $row['prenom'];
            $this->nom = $row['nom'];

            return parent::RechercheUtilisateur($row['id_utilisateur']);
        } catch (PDOException) {
            return false;
        }
    }




    public function Update($adrMail)
    {
        try {
            $req = "UPDATE Client Set prenom = :prenom, nom = :nom WHERE adrMail = :adrMail";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindParam(':adrMail', $adrMail);
            $stmt->bindParam(':prenom', $this->prenom);
            $stmt->bindParam(':nom', $this->nom);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour des données : " . $e->GetMessage();
        }
    }

    public function Delete($adrMail)
    {
        try {
            $req = "DELETE FROM Client WHERE adrMail = :adrMail";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindParam(':adrMail', $adrMail);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression des données : " . $e->GetMessage();
        }
    }
}
