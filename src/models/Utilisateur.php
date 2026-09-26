<?php
class Utilisateur
{
    private $pdo;
    protected $idUtilisateur;
    protected $mdp;
    protected $estValide;
    protected $token;
    protected $tokenExpire;
    protected $dateCreation;

    function __construct(PDO $pdo, $idUtilisateur, $mdp, $estValide, $token, $tokenExpire, $dateCreation = null)
    {
        $this->pdo = $pdo;
        $this->idUtilisateur = $idUtilisateur;
        $this->mdp = $mdp;
        $this->estValide = $estValide;
        $this->token = $token;
        $this->tokenExpire = $tokenExpire;
        $this->dateCreation = $dateCreation;
    }

    public function GetIdUtilisateur()
    {
        return $this->idUtilisateur;
    }
    public function GetMdp()
    {
        return $this->mdp;
    }
    public function GetestValide()
    {
        return $this->estValide;
    }
    public function GetToken()
    {
        return $this->token;
    }
    public function GettokenExpire()
    {
        return $this->tokenExpire;
    }
    public function GetdateCreation()
    {
        return $this->dateCreation;
    }

    public function SetIdUtilisateur($idUtilisateur)
    {
        $this->idUtilisateur = $idUtilisateur;
    }
    public function SetMdp($mdp)
    {
        $this->mdp = $mdp;
    }
    public function SetestValide($estValide)
    {
        $this->estValide = $estValide;
    }
    public function SetToken($token)
    {
        $this->token = $token;
    }
    public function SettokenExpire($tokenExpire)
    {
        $this->tokenExpire = $tokenExpire;
    }
    public function SetdateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }

    // CREATE
    public function CreationUtilisateur()
    {
        try {
            $req = "INSERT INTO Utilisateur (id_utilisateur, mdp, est_valide, token, token_expire, date_creation) 
                VALUES (:id_utilisateur, :mdp, :est_valide, :token, :token_expire, :date_creation)";

            $stmt = $this->pdo->prepare($req);
            $stmt->bindParam('id_utilisateur', $this->idUtilisateur);
            $stmt->bindParam('mdp', $this->mdp);
            $stmt->bindParam('est_valide', $this->estValide);
            $stmt->bindParam('token', $this->token);
            $stmt->bindParam('token_expire', $this->tokenExpire);
            $stmt->bindParam('date_creation', $this->dateCreation);

            return $stmt->execute();

        } catch (PDOException) {
            return false;
        }
    }

    // READ
    public function RechercheUtilisateur($idUtilisateur)
    {
        try {
            $req = "SELECT mdp, est_valide, token, token_expire, date_creation FROM Utilisateur
                WHERE id_utilisateur = :id_utilisateur";

            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':id_utilisateur', $idUtilisateur);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return false;
            }
            $this->idUtilisateur = $idUtilisateur;
            $this->mdp = $row['mdp'];
            $this->estValide = $row['est_valide'];
            $this->token = $row['token'];
            $this->tokenExpire = $row['token_expire'];
            $this->dateCreation = $row['date_creation'];

            return true;
        } catch (PDOException) {
            return false;
        }
    }

    

    // DELETE
    public function SuppUtilisateur()
    {
        try {
            $req = "DELETE FROM Utilisateur WHERE id_utilisateur = :idUtilisateur";

            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':id', $this->idUtilisateur);
            return $stmt->execute();
        }
    }
}