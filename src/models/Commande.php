<?php

class Commande
{
    private $pdo;
    private $idCommande;
    private $dateHeure;
    private $statut;

    function __construct(PDO $pdo, $idCommande, $dateHeure, $statut)
    {
        $this->pdo = $pdo;
        $this->idCommande = $idCommande;
        $this->dateHeure = $dateHeure;
        $this->statut = $statut;
    }

    public function GetIdCommande()
    {
        return $this->idCommande;
    }
    public function GetDateHeure()
    {
        return $this->dateHeure;
    }
    public function GetStatut()
    {
        return $this->statut;
    }

    public function SetIdCommande($idCommande)
    {
        $this->idCommande = $idCommande;
    }
    public function SetDateHeure($dateHeure)
    {
        $this->dateHeure = $dateHeure;
    }
    public function SetStatut($statut)
    {
        $this->statut = $statut;
    }

    function GetMax()
    {
        $stmt = $this->pdo->prepare("SELECT MAX(idCommande) AS max FROM Commande");
        $stmt->execute();
        $max = $stmt->fetch(PDO::FETCH_ASSOC);
        return $max["max"] + 1;
    }

    public function Create()
    {
        try {
            $req = "INSERT INTO Commande(idCommande, dateHeure, statut) VALUES (:idCommande, :dateHeure, :statut)";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':idCommande', $this->GetMax());
            $stmt->bindParam(':dateHeure', $this->dateHeure);
            $stmt->bindParam(':statut', $this->statut);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de la création des données : " . $e->GetMessage();
            return false;
        }
    }

    public function findAll()
    {
        try {
            $req = "SELECT idCommande, dateHeure, statut FROM Commande";
            $stmt = $this->pdo->prepare($req);
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des données : " . $e->GetMessage();
        }
    }

    public function Delete($idCommande)
    {
        try {
            $req = "DELETE FROM Commande WHERE idCommande = :idCommande";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindParam(':idCommande', $idCommande);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression des données : " . $e->GetMessage();
        }
    }
}
