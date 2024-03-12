<?php
// Definitie van de GroepenModelklasse
class GroepenModel
{
    private $conn; // PDO-verbinding

    // Constructor om een databaseverbinding tot stand te brengen
    public function __construct()
    {
        $database = new Database(); // Instantiëring van de Databaseklasse
        $this->conn = $database->connect(); // Verbinding maken met de database
    }

    // Methode om alle groepen op te halen
    public function getGroups()
    {
        try {
            $query = 'SELECT * FROM Soort_lid'; // SQL-query om alle groepen op te halen uit de Soort_lid tabel
            $stmt = $this->conn->prepare($query); // Voorbereiden van de query
            $stmt->execute(); // Uitvoeren van de query
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourneren van de resultaten
        } catch (PDOException $e) {
            echo "Er is een fout opgetreden: " . $e->getMessage(); // Weergeven van de foutmelding bij een PDO-uitzondering
            return false; // Retourneren van false bij een fout
        }
    }

    // Methode om een nieuwe groep toe te voegen
    public function addGroup($soortnaam, $omschrijving, $korting)
    {
        try {
            $query = "INSERT INTO Soort_lid (soortnaam, Omschrijving, korting) VALUES (:soortnaam, :omschrijving, :korting)"; // SQL-query om een nieuwe groep toe te voegen
            $stmt = $this->conn->prepare($query); // Voorbereiden van de query
            $stmt->bindParam(':soortnaam', $soortnaam); // Binding van parameters
            $stmt->bindParam(':omschrijving', $omschrijving);
            $stmt->bindParam(':korting', $korting);
            return $stmt->execute(); // Uitvoeren van de query en retourneren van true bij succesvolle uitvoering
        } catch (PDOException $e) {
            echo "Er is een fout opgetreden: " . $e->getMessage(); // Weergeven van de foutmelding bij een PDO-uitzondering
            return false; // Retourneren van false bij een fout
        }
    }

    // Methode om een groep aan te passen
    public function editGroup($groupId, $newName, $newDescription, $newDiscount)
    {
        try {
            $query = "UPDATE Soort_lid SET soortnaam = :soortnaam, Omschrijving = :omschrijving, korting = :korting WHERE soortLidID = :groupId"; // SQL-query om een groep aan te passen op basis van de groeps-ID
            $stmt = $this->conn->prepare($query); // Voorbereiden van de query
            $stmt->bindParam(':soortnaam', $newName); // Binding van parameters
            $stmt->bindParam(':omschrijving', $newDescription);
            $stmt->bindParam(':korting', $newDiscount);
            $stmt->bindParam(':groupId', $groupId);
            return $stmt->execute(); // Uitvoeren van de query en retourneren van true bij succesvolle uitvoering
        } catch (PDOException $e) {
            echo "Er is een fout opgetreden: " . $e->getMessage(); // Weergeven van de foutmelding bij een PDO-uitzondering
            return false; // Retourneren van false bij een fout
        }
    }

    // Methode om een groep te verwijderen
    public function deleteGroup($groupId)
    {
        try {
            $query = "DELETE FROM Soort_lid WHERE soortLidID = :groupId"; // SQL-query om een groep te verwijderen op basis van de groeps-ID
            $stmt = $this->conn->prepare($query); // Voorbereiden van de query
            $stmt->bindParam(':groupId', $groupId); // Binding van parameters
            return $stmt->execute(); // Uitvoeren van de query en retourneren van true bij succesvolle uitvoering
        } catch (PDOException $e) {
            echo "Er is een fout opgetreden: " . $e->getMessage(); // Weergeven van de foutmelding bij een PDO-uitzondering
            return false; // Retourneren van false bij een fout
        }
    }
}
?>
