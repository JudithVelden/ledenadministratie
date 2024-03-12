<?php
// Inclusie van de PDO-verbinding
include_once './model/pdoconnectie.php';

// Definitie van de Boekjaarmodelklasse
class Boekjaarmodel
{
    private $conn; // PDO-verbinding
    private $table = 'Boekjaar'; // Tabelnaam

    // Constructor om een databaseverbinding tot stand te brengen
    public function __construct()
    {
        $database = new Database(); // Instantiëring van de Databaseklasse
        $this->conn = $database->connect(); // Verbinding maken met de database
    }

    // Methode om boekjaren op te halen met aantal leden
    public function getBoekjaren()
    {
        // SQL-query om boekjaren op te halen met aantal leden
        $query = 'SELECT b.ID AS boekjaar_id, b.Jaar AS boekjaar, COUNT(DISTINCT fl.ID) AS aantal_leden
                  FROM Boekjaar b
                  LEFT JOIN Contributie c ON b.ID = c.Boekjaar_id
                  LEFT JOIN Familielid fl ON c.Familielid_id = fl.ID
                  GROUP BY b.ID, b.Jaar';

        $stmt = $this->conn->prepare($query); // Voorbereiden van de query
        $stmt->execute(); // Uitvoeren van de query
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Ophalen van alle rijen als een associatieve array
    }

    // Methode om te controleren of een boekjaar bestaat
    public function boekjaarExists($jaar)
    {
        // SQL-query om te controleren of een boekjaar bestaat
        $query = "SELECT COUNT(*) AS total FROM Boekjaar WHERE Jaar = :jaar";
        $stmt = $this->conn->prepare($query); // Voorbereiden van de query
        $stmt->bindParam(':jaar', $jaar); // Binding van parameter
        $stmt->execute(); // Uitvoeren van de query
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Ophalen van het resultaat als een associatieve array
        return $result['total'] > 0; // Retourneer true als het boekjaar bestaat, anders false
    }

    // Methode om een boekjaar toe te voegen
    public function addBoekjaar($jaar)
    {
        // SQL-query om een boekjaar toe te voegen
        $query = "INSERT INTO Boekjaar (Jaar) VALUES (:jaar)";
        $stmt = $this->conn->prepare($query); // Voorbereiden van de query
        $stmt->bindParam(':jaar', $jaar); // Binding van parameter
        $stmt->execute(); // Uitvoeren van de query
    }

    // Methode om een boekjaar bij te werken
    public function updateBoekjaar($boekjaar_id, $nieuw_jaar)
    {
        // Controleer eerst of het nieuwe jaar al bestaat in de database
        if ($this->boekjaarExists($nieuw_jaar)) {
            // Als het nieuwe jaar al bestaat, retourneer false of geef een foutmelding terug
            return false;
        } else {
            // Als het nieuwe jaar nog niet bestaat, voer de update uit
            $query = 'UPDATE ' . $this->table . ' SET Jaar = :jaar WHERE ID = :boekjaar_id';
            $stmt = $this->conn->prepare($query); // Voorbereiden van de query
            $stmt->bindParam(':jaar', $nieuw_jaar); // Binding van parameter
            $stmt->bindParam(':boekjaar_id', $boekjaar_id); // Binding van parameter
            $stmt->execute(); // Uitvoeren van de query
            return true; // Retourneer true om aan te geven dat de update succesvol is uitgevoerd
        }
    }

    // Methode om een boekjaar te verwijderen
    public function deleteBoekjaar($boekjaar_id)
    {
        // Verwijder het boekjaar uit de database op basis van het opgegeven ID
        $query = "DELETE FROM " . $this->table . " WHERE ID = :boekjaar_id";
        $stmt = $this->conn->prepare($query); // Voorbereiden van de query
        $stmt->bindParam(':boekjaar_id', $boekjaar_id); // Binding van parameter
        $stmt->execute(); // Uitvoeren van de query
    }
}
?>
