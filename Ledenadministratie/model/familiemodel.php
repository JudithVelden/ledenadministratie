<?php
// Inclusie van de PDO-verbinding
include_once './model/pdoconnectie.php';

// Definitie van de Familieklasse
class Familie
{
    private $conn; // PDO-verbinding
    private $table = 'Familie'; // Tabelnaam

    // Constructor om een databaseverbinding tot stand te brengen
    public function __construct()
    {
        $database = new Database(); // Instantiëring van de Databaseklasse
        $this->conn = $database->connect(); // Verbinding maken met de database
    }

    // Methode om alle families op te halen
    public function getFamilies()
    {
        $query = 'SELECT * FROM ' . $this->table; // SQL-query om alle families op te halen
        $stmt = $this->conn->prepare($query); // Voorbereiden van de query
        $stmt->execute(); // Uitvoeren van de query
        return $stmt; // Retourneren van de resultaten
    }

    // Methode om een nieuwe familie toe te voegen
    public function addFamilie($naam, $adres)
    {
        $query = 'INSERT INTO ' . $this->table . ' (Naam, Adres) VALUES (:naam, :adres)'; // SQL-query om een nieuwe familie toe te voegen
        $stmt = $this->conn->prepare($query); // Voorbereiden van de query
        $stmt->bindParam(':naam', $naam); // Binding van parameters
        $stmt->bindParam(':adres', $adres);
        if ($stmt->execute()) { // Uitvoeren van de query en controleren op succes
            return true; // Retourneren van true bij succesvolle uitvoering
        }
        $errorInfo = $stmt->errorInfo(); // Ophalen van foutinformatie bij mislukte uitvoering
        printf("Error: %s.\n", $errorInfo[2]); // Weergeven van de foutmelding
        return false; // Retourneren van false bij mislukte uitvoering
    }

    // Methode om een familie te bewerken
    public function editFamily($familyID, $newName, $newAddress)
    {
        $query = 'UPDATE ' . $this->table . ' SET Naam = :newName, Adres = :newAddress WHERE ID = :familyID'; // SQL-query om een familie te bewerken op basis van het familie-ID
        $stmt = $this->conn->prepare($query); // Voorbereiden van de query
        $stmt->bindParam(':familyID', $familyID); // Binding van parameters
        $stmt->bindParam(':newName', $newName);
        $stmt->bindParam(':newAddress', $newAddress);
        if ($stmt->execute()) { // Uitvoeren van de query en controleren op succes
            return true; // Retourneren van true bij succesvolle uitvoering
        }
        $errorInfo = $stmt->errorInfo(); // Ophalen van foutinformatie bij mislukte uitvoering
        printf("Error: %s.\n", $errorInfo[2]); // Weergeven van de foutmelding
        return false; // Retourneren van false bij mislukte uitvoering
    }

    // Methode om een familie te verwijderen
    public function deleteFamily($familyID)
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE ID = :familyID'; // SQL-query om een familie te verwijderen op basis van het familie-ID
        $stmt = $this->conn->prepare($query); // Voorbereiden van de query
        $stmt->bindParam(':familyID', $familyID); // Binding van parameters
        if ($stmt->execute()) { // Uitvoeren van de query en controleren op succes
            return true; // Retourneren van true bij succesvolle uitvoering
        }
        $errorInfo = $stmt->errorInfo(); // Ophalen van foutinformatie bij mislukte uitvoering
        printf("Error: %s.\n", $errorInfo[2]); // Weergeven van de foutmelding
        return false; // Retourneren van false bij mislukte uitvoering
    }
}
?>
