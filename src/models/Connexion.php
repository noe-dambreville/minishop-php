<?php

class Connexion
{
    private $pdo;
    private $idConnexion;
    private $adrIP;
    private $dateHeure;
    private $statut;

    function __construct(PDO $pdo, $idConnexion, $adrIP, $dateHeure, $statut)
    {
        $this->pdo = $pdo;
        $this->idConnexion = $idConnexion;
        $this->adrIP = $adrIP;
        $this->dateHeure = $dateHeure;
        $this->statut = $statut;
    }

    public function getIdConnexion()
    {
        return $this->idConnexion;
    }
    public function getAdrIP()
    {
        return $this->adrIP;
    }
    public function getDateHeure()
    {
        return $this->dateHeure;
    }
    public function getStatut()
    {
        return $this->statut;
    }

    public function setIdConnexion($idConnexion)
    {
        $this->idConnexion = $idConnexion;
    }
    public function setAdrIP($adrIP)
    {
        $this->adrIP = $adrIP;
    }
    public function setDateHeure($dateHeure)
    {
        $this->dateHeure = $dateHeure;
    }
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

    function getMax()
    {
        $stmt = $this->pdo->prepare("SELECT MAX(idConnexion) AS max FROM Connexion");
        $stmt->execute();
        $max = $stmt->fetch(PDO::FETCH_ASSOC);
        return $max["max"] + 1;
    }

    public function Create()
    {
        try {
            $req = "INSERT INTO Connexion(idConnexion, adrIP, dateHeure, statut) VALUES (:idConnexion, :adrIP, :dateHeure, :statut)";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':idConnexion', $this->getMax());
            $stmt->bindParam(':adrIP', $this->adrIP);
            $stmt->bindParam(':dateHeure', $this->dateHeure);
            $stmt->bindParam(':statut', $this->statut);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de la création des données : " . $e->getMessage();
            return false;
        }
    }

    public function Delete($idConnexion)
    {
        try {
            $req = "DELETE FROM Connexion WHERE idConnexion = :idConnexion";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindParam(':idConnexion', $idConnexion);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression des données : " . $e->getMessage();
        }
    }
}
