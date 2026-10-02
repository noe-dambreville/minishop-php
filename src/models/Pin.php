<?php
require_once __DIR__ . '/../traits/Generateur.php';
require_once __DIR__ . '/../traits/Chiffrement.php';

class Pin
{
    use Generateur;
    use Chiffrement;

    private $pdo;
    private $idPin;
    private $codePin;
    private $dateExpire;
    private $tentatives;
    private $idUtilisateur;

    function __construct(PDO $pdo, $codePin = null, $dateExpire = null, $tentatives = 0, $idUtilisateur = null)
    {
        $this->pdo = $pdo;
        $this->codePin = $codePin;
        $this->dateExpire = $dateExpire;
        $this->tentatives = $tentatives;
        $this->idUtilisateur = $idUtilisateur;
    }

    // GETTERS
    public function GetIdPin()
    {
        return $this->idPin;
    }
    public function GetCodePin()
    {
        return $this->codePin;
    }
    public function GetDateExpire()
    {
        return $this->dateExpire;
    }
    public function GetTentatives()
    {
        return $this->tentatives;
    }
    public function GetIdUtilisateur()
    {
        return $this->idUtilisateur;
    }

    // SETTERS
    public function SetCodePin($codePin)
    {
        $this->codePin = $codePin;
    }
    public function SetDateExpire($dateExpire)
    {
        $this->dateExpire = $dateExpire;
    }
    public function SetTentatives($tentatives)
    {
        $this->tentatives = $tentatives;
    }
    public function SetIdUtilisateur($idUtilisateur)
    {
        $this->idUtilisateur = $idUtilisateur;
    }

    public function EstExpire()
    {
        return $this->dateExpire === null || new DateTime($this->dateExpire) < new DateTime();
    }

    public function EstCorrect($codeSaisi)
    {
        return $this->Verifier((string) $codeSaisi, $this->codePin);
    }

    public function TropDeTentatives($seuil = 3)
    {
        return $this->tentatives >= $seuil;
    }

    // CREATE (ou délègue à )
    public function Enregistrer()
    {
        $codePinHache = $this->Hasher($this->codePin);
        $dateExpire = $this->dateExpire;

        if ($this->RechercheParUtilisateur($this->idUtilisateur)) {
            $this->codePin = $codePinHache;
            $this->dateExpire = $dateExpire;

            return $this->MajPin($this->idUtilisateur);
        }

        try {
            $req = "INSERT INTO Pin (id_pin, code_pin, date_expire, tentatives, id_utilisateur)
                VALUES (:id_pin, :code_pin, :date_expire, 0, :id_utilisateur)";

            $this->idPin = $this->GenerationId();
            $this->codePin = $codePinHache;
            $this->dateExpire = $dateExpire;
            $this->tentatives = 0;

            $stmt = $this->pdo->prepare($req);
            $stmt->bindParam(':id_pin', $this->idPin);
            $stmt->bindParam(':code_pin', $this->codePin);
            $stmt->bindParam(':date_expire', $this->dateExpire);
            $stmt->bindParam(':id_utilisateur', $this->idUtilisateur);

            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }

    // READ
    public function RechercheParUtilisateur($idUtilisateur)
    {
        try {
            $req = "SELECT id_pin, code_pin, date_expire, tentatives FROM Pin WHERE id_utilisateur = :id_utilisateur";

            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':id_utilisateur', $idUtilisateur);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return false;
            }

            $this->idPin = $row['id_pin'];
            $this->codePin = $row['code_pin'];
            $this->dateExpire = $row['date_expire'];
            $this->tentatives = $row['tentatives'];
            $this->idUtilisateur = $idUtilisateur;

            return true;
        } catch (PDOException) {
            return false;
        }
    }

    // UPDATE
    // Si un Pin existe déjà pour ce compte
    public function MajPin($idUtilisateur)
    {
        try {
            $req = "UPDATE Pin SET code_pin = :code_pin, date_expire = :date_expire, tentatives = 0
                WHERE id_utilisateur = :id_utilisateur";

            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':id_utilisateur', $idUtilisateur);
            $stmt->bindParam(':code_pin', $this->codePin);
            $stmt->bindParam(':date_expire', $this->dateExpire);

            $this->tentatives = 0;

            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }

    public function IncrementerTentatives()
    {
        try {
            $req = "UPDATE Pin SET tentatives = tentatives + 1 WHERE id_pin = :id_pin";

            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':id_pin', $this->idPin);

            $this->tentatives++;

            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }

    // DELETE
    public function Supprimer()
    {
        try {
            $req = "DELETE FROM Pin WHERE id_pin = :id_pin";

            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':id_pin', $this->idPin);

            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }
}
