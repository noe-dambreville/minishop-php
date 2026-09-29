<?php

require_once __DIR__ . '/../traits/Chiffrement.php';
require_once __DIR__ . '/../traits/Generateur.php';

class Utilisateur
{
    use Chiffrement;
    use Generateur;
    protected $pdo;
    protected $idUtilisateur;
    protected $mdp;
    protected $estValide;
    protected $token;
    protected $tokenExpire;
    protected $dateCreation;
    protected $role;

    function __construct(PDO $pdo, $idUtilisateur = null, $mdp = null, $estValide = null, $token = null, $tokenExpire = null, $dateCreation = null, $role = 'client')
    {
        $this->pdo = $pdo;
        $this->idUtilisateur = $idUtilisateur;
        $this->mdp = $mdp;
        $this->estValide = $estValide;
        $this->token = $token;
        $this->tokenExpire = $tokenExpire;
        $this->dateCreation = $dateCreation;
        $this->role = $role;
    }

    // GETTERS
    public function GetIdUtilisateur()
    {
        return $this->idUtilisateur;
    }
    public function GetMdp()
    {
        return $this->mdp;
    }
    public function GetEstValide()
    {
        return $this->estValide;
    }
    public function GetToken()
    {
        return $this->token;
    }
    public function GetTokenExpire()
    {
        return $this->tokenExpire;
    }
    public function GetDateCreation()
    {
        return $this->dateCreation;
    }
    public function GetRole()
    {
        return $this->role;
    }

    // SETTERS
    public function SetIdUtilisateur($idUtilisateur)
    {
        $this->idUtilisateur = $idUtilisateur;
    }
    public function SetMdp($mdp)
    {
        $this->mdp = $mdp;
    }
    public function SetEstValide($estValide)
    {
        $this->estValide = $estValide;
    }
    public function SetToken($token)
    {
        $this->token = $token;
    }
    public function SetTokenExpire($tokenExpire)
    {
        $this->tokenExpire = $tokenExpire;
    }
    public function SetDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }
    public function SetRole($role)
    {
        $this->role = $role;
    }


    public function VerifSession()
    {
        $idConnecte = $_SESSION['id_utilisateur'] ?? null;
        $tokenConnecte = $_SESSION['token'] ?? null;

        if ($idConnecte === null || $tokenConnecte === null) {
            return false;
        }

        if (!$this->RechercheUtilisateur($idConnecte)) {
            return false;
        }

        if ($this->GetToken() === null || !hash_equals($this->GetToken(), $tokenConnecte)) {
            return false;
        }

        if ($this->GetTokenExpire() === null || new DateTime($this->GetTokenExpire()) < new DateTime()) {
            return false;
        }

        return true;
    }

    // CRUD //

    // CREATE
    public function CreationUtilisateur()
    {
        try {
            $req = "INSERT INTO Utilisateur (id_utilisateur, mdp, est_valide, token, token_expire, date_creation, role)
                VALUES (:id_utilisateur, :mdp, :est_valide, :token, :token_expire, :date_creation, :role)";

            $stmt = $this->pdo->prepare($req);
            $stmt->bindParam(':id_utilisateur', $this->idUtilisateur);
            $stmt->bindParam(':mdp', $this->mdp);
            $stmt->bindParam(':est_valide', $this->estValide);
            $stmt->bindParam(':token', $this->token);
            $stmt->bindParam(':token_expire', $this->tokenExpire);
            $stmt->bindParam(':date_creation', $this->dateCreation);
            $stmt->bindParam(':role', $this->role);

            $ok = $stmt->execute();

            return $ok;
        } catch (PDOException) {
            return false;
        }
    }

    // READ
    public function RechercheUtilisateur($idUtilisateur)
    {
        try {
            $req = "SELECT mdp, est_valide, token, token_expire, date_creation, role FROM Utilisateur
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
            $this->role = $row['role'];

            return true;
        } catch (PDOException) {
            return false;
        }
    }

    // UPDATE
    public function MajUtilisateur($idUtilisateur)
    {
        try {
            $req = "UPDATE Utilisateur SET mdp = :mdp, est_valide = :est_valide, token = :token, token_expire = :token_expire, date_creation = :date_creation, role = :role
                WHERE id_utilisateur = :id_utilisateur";

            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':id_utilisateur', $idUtilisateur);
            $stmt->bindParam(':mdp', $this->mdp);
            $stmt->bindParam(':est_valide', $this->estValide);
            $stmt->bindParam(':token', $this->token);
            $stmt->bindParam(':token_expire', $this->tokenExpire);
            $stmt->bindParam(':date_creation', $this->dateCreation);
            $stmt->bindParam(':role', $this->role);

            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }

    // DELETE
    public function SuppUtilisateur($idUtilisateur)
    {
        try {
            $req = "DELETE FROM Utilisateur WHERE id_utilisateur = :id_utilisateur";

            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':id_utilisateur', $idUtilisateur);

            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }
}