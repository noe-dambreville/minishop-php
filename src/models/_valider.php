<?php

class Valider
{
    private $pdo;
    private $idCommande;
    private $date_validation;
    private $idUtilisateur;

    function __construct(PDO $pdo, $idCommande, $date_validation, $idUtilisateur)
    {
        $this->pdo = $pdo;
        $this->idCommande = $idCommande;
        $this->date_validation = $date_validation;
        $this->idUtilisateur = $idUtilisateur;
    }

    public function GetIdCommande()
    {
        return $this->idCommande;
    }
    public function GetDate_validation()
    {
        return $this->date_validation;
    }
    public function GetIdUtilisateur()
    {
        return $this->idUtilisateur;
    }

    public function SetIdCommande($idCommande)
    {
        $this->idCommande = $idCommande;
    }
    public function SetDate_validation($date_validation)
    {
        $this->date_validation = $date_validation;
    }
    public function SetIdUtilisateur($idUtilisateur)
    {
        $this->idUtilisateur = $idUtilisateur;
    }

    function GetMax()
    {
        $stmt = $this->pdo->prepare("SELECT MAX(idCommande) AS max FROM Validation");
        $stmt->execute();
        $max = $stmt->fetch(PDO::FETCH_ASSOC);
        return $max["max"] + 1;
    }

    public function Create()
    {
        try {
            $req = "INSERT INTO Validation(idCommande, date_validation, idUtilisateur) VALUES (:idCommande, :date_validation, :idUtilisateur)";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':idCommande', $this->GetMax());
            $stmt->bindParam(':date_validation', $this->date_validation);
            $stmt->bindParam(':idUtilisateur', $this->idUtilisateur);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de la création des données : " . $e->GetMessage();
            return false;
        }
    }

    public function Read($idCommande)
    {
        try {
            $req = "SELECT idCommande, date_validation, idUtilisateur FROM Validation WHERE idCommande = :idCommande";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindParam(':idCommande', $idCommande);
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($res) {
                $this->idCommande = $res['idCommande'];
                $this->date_validation = $res['date_validation'];
                $this->idUtilisateur = $res['idUtilisateur'];
                return true;
            }
            return false;
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des données : " . $e->GetMessage();
            return false;
        }
    }

    public function Delete($idCommande)
    {
        try {
            $req = "DELETE FROM Validation WHERE idCommande = :idCommande";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindParam(':idCommande', $idCommande);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression des données : " . $e->GetMessage();
        }
    }
}
