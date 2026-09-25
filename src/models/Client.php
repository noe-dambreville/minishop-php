<?php
require_once '../traits/Generateur.php';

class Client extends Utilisateur
{
    use Generateur;
    private $pdo;
    private $adr_mail;
    private $prenom;
    private $nom;

    function __construct(PDO $pdo, $adr_mail, $prenom, $nom, $idUtilisateur, $mdp, $est_valide, $token, $token_valide, $date_creation)
    {
        parent::__construct($idUtilisateur, $mdp, $est_valide, $token, $token_valide, $date_creation);
        $this->pdo = $pdo;
        $this->adr_mail = $adr_mail;
        $this->prenom = $prenom;
        $this->nom = $nom;
    }

    public function GetAdr_mail()
    {
        return $this->adr_mail;
    }
    public function GetPrenom()
    {
        return $this->prenom;
    }
    public function GetNom()
    {
        return $this->nom;
    }

    public function SetAdr_mail($adr_mail)
    {
        $this->adr_mail = $adr_mail;
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
    // public function CreationClient(){
    //     $req = "";
    //     $stmt = $this->pdo->prepare($req);
    //     $stmt->bindParam

    // }






    public function Creatazee()
    {
        try {
            $req = "INSERT INTO Client(adr_mail, prenom, nom) VALUES (:adr_mail, :prenom, :nom)";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':adr_mail', $this->id());
            $stmt->bindParam(':prenom', $this->prenom);
            $stmt->bindParam(':nom', $this->nom);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de la création des données : " . $e->GetMessage();
            return false;
        }
    }

    public function Read($adr_mail)
    {
        try {
            $req = "SELECT adr_mail, prenom, nom FROM Client WHERE adr_mail = :adr_mail";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindParam(':adr_mail', $adr_mail);
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($res) {
                $this->adr_mail = $res['adr_mail'];
                $this->prenom = $res['prenom'];
                $this->nom = $res['nom'];
                return true;
            }
            return false;
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des données : " . $e->GetMessage();
            return false;
        }
    }

    public function Update($adr_mail)
    {
        try {
            $req = "UPDATE Client Set prenom = :prenom, nom = :nom WHERE adr_mail = :adr_mail";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindParam(':adr_mail', $adr_mail);
            $stmt->bindParam(':prenom', $this->prenom);
            $stmt->bindParam(':nom', $this->nom);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour des données : " . $e->GetMessage();
        }
    }

    public function Delete($adr_mail)
    {
        try {
            $req = "DELETE FROM Client WHERE adr_mail = :adr_mail";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindParam(':adr_mail', $adr_mail);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression des données : " . $e->GetMessage();
        }
    }
}
