<?php

class Produit
{
    private $pdo;
    private $idProduit;
    private $nom;
    private $description;
    private $prix_unitaire;
    private $quantite_stock;

    function __construct(PDO $pdo, $idProduit, $nom, $description, $prix_unitaire, $quantite_stock)
    {
        $this->pdo = $pdo;
        $this->idProduit = $idProduit;
        $this->nom = $nom;
        $this->description = $description;
        $this->prix_unitaire = $prix_unitaire;
        $this->quantite_stock = $quantite_stock;
    }

    public function GetIdProduit()
    {
        return $this->idProduit;
    }
    public function GetNom()
    {
        return $this->nom;
    }
    public function GetDescription()
    {
        return $this->description;
    }
    public function GetPrix_unitaire()
    {
        return $this->prix_unitaire;
    }
    public function GetQuantite_stock()
    {
        return $this->quantite_stock;
    }

    public function SetIdProduit($idProduit)
    {
        $this->idProduit = $idProduit;
    }
    public function SetNom($nom)
    {
        $this->nom = $nom;
    }
    public function SetDescription($description)
    {
        $this->description = $description;
    }
    public function SetPrix_unitaire($prix_unitaire)
    {
        $this->prix_unitaire = $prix_unitaire;
    }
    public function SetQuantite_stock($quantite_stock)
    {
        $this->quantite_stock = $quantite_stock;
    }

    function GetMax()
    {
        $stmt = $this->pdo->prepare("SELECT MAX(idProduit) AS max FROM Produit");
        $stmt->execute();
        $max = $stmt->fetch(PDO::FETCH_ASSOC);
        return $max["max"] + 1;
    }

    public function Create()
    {
        try {
            $req = "INSERT INTO Produit(idProduit, nom, description, prix_unitaire, quantite_stock) VALUES (:idProduit, :nom, :description, :prix_unitaire, :quantite_stock)";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindValue(':idProduit', $this->GetMax());
            $stmt->bindParam(':nom', $this->nom);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':prix_unitaire', $this->prix_unitaire);
            $stmt->bindParam(':quantite_stock', $this->quantite_stock);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de la création des données : " . $e->GetMessage();
            return false;
        }
    }

    public function Read($idProduit)
    {
        try {
            $req = "SELECT idProduit, nom, description, prix_unitaire, quantite_stock FROM Produit WHERE idProduit = :idProduit";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindParam(':idProduit', $idProduit);
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($res) {
                $this->idProduit = $res['idProduit'];
                $this->nom = $res['nom'];
                $this->description = $res['description'];
                $this->prix_unitaire = $res['prix_unitaire'];
                $this->quantite_stock = $res['quantite_stock'];
                return true;
            }
            return false;
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des données : " . $e->GetMessage();
            return false;
        }
    }

    public function findAll()
    {
        try {
            $req = "SELECT idProduit, nom, description, prix_unitaire, quantite_stock FROM Produit";
            $stmt = $this->pdo->prepare($req);
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des données : " . $e->GetMessage();
        }
    }

    public function Update($idProduit)
    {
        try {
            $req = "UPDATE Produit Set nom = :nom, description = :description, prix_unitaire = :prix_unitaire, quantite_stock = :quantite_stock WHERE idProduit = :idProduit";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindParam(':idProduit', $idProduit);
            $stmt->bindParam(':nom', $this->nom);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':prix_unitaire', $this->prix_unitaire);
            $stmt->bindParam(':quantite_stock', $this->quantite_stock);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour des données : " . $e->GetMessage();
        }
    }

    public function Delete($idProduit)
    {
        try {
            $req = "DELETE FROM Produit WHERE idProduit = :idProduit";
            $stmt = $this->pdo->prepare($req);
            $stmt->bindParam(':idProduit', $idProduit);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression des données : " . $e->GetMessage();
        }
    }
}
