<?php
// Inclusie van de PDO-verbinding
include_once './model/pdoconnectie.php';

// Definitie van de ContributiesModelklasse
class ContributiesModel
{
    private $conn; // PDO-verbinding
    private $table = 'Contributie'; // Tabelnaam

    // Constructor om een databaseverbinding tot stand te brengen
    public function __construct()
    {
        $database = new Database(); // Instantiëring van de Databaseklasse
        $this->conn = $database->connect(); // Verbinding maken met de database
    }

    // Methode om alle contributies op te halen
    public function getContributions()
    {
        // SQL-query om contributies op te halen met gerelateerde informatie over familielid, familie en boekjaar
        $query = 'SELECT c.ID AS contributie_id, fl.voornaam, f.Naam AS achternaam, fl.ID AS familielid_id, fl.Familie_id, c.Bedrag, b.Jaar AS boekjaar
                  FROM Contributie c
                  JOIN Familielid fl ON c.familielid_id = fl.ID
                  JOIN Familie f ON fl.Familie_id = f.ID
                  JOIN Boekjaar b ON c.boekjaar_id = b.ID';
        $stmt = $this->conn->prepare($query); // Voorbereiden van de query
        $stmt->execute(); // Uitvoeren van de query
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Ophalen van alle rijen als een associatieve array
    }

    // Methode om het Boekjaar_id op te halen op basis van het geselecteerde jaar
    private function getBoekjaarId($selectedYear)
    {
        $query = "SELECT ID FROM Boekjaar WHERE Jaar = :jaar";
        $stmt = $this->conn->prepare($query); // Voorbereiden van de query
        $stmt->bindParam(':jaar', $selectedYear, PDO::PARAM_STR); // Binding van parameter
        $stmt->execute(); // Uitvoeren van de query
        $boekjaarId = $stmt->fetchColumn(); // Ophalen van het resultaat
        return $boekjaarId; // Retourneren van het Boekjaar_id
    }

    // Methode om een contributie toe te voegen voor een familielid
    public function addContributionForFamilyMember($selectedYear, $familielidId)
    {
        // Haal het Boekjaar_id op basis van het opgegeven jaar
        $boekjaarId = $this->getBoekjaarId($selectedYear);

        // Bereken de leeftijd en contributie
        $leeftijd = $this->calculateAge($selectedYear, $familielidId);
        $bedrag = $this->calculateContribution($leeftijd);

        // Voorbereid de invoegquery
        $query = "INSERT INTO Contributie (Boekjaar_id, Familielid_id, Leeftijd, Soort_lid_id, Bedrag)
              VALUES (:boekjaarId, :familielidId, :leeftijd, (SELECT Soort_lid_id FROM Familielid WHERE ID = :familielidId), :bedrag)";

        // Bereid de query voor gebruik met PDO
        $stmt = $this->conn->prepare($query);

        // Bind de parameters aan de query
        $stmt->bindParam(':boekjaarId', $boekjaarId, PDO::PARAM_INT);
        $stmt->bindParam(':familielidId', $familielidId, PDO::PARAM_INT);
        $stmt->bindParam(':leeftijd', $leeftijd, PDO::PARAM_INT);
        $stmt->bindParam(':bedrag', $bedrag, PDO::PARAM_INT);

        // Voer de query uit
        $stmt->execute();
    }

    // Functie om leeftijd te berekenen op basis van het geselecteerde jaar en familielid
    private function calculateAge($selectedYear, $familielidId)
    {
        // Haal de geboortedatum op van het familielid
        $query = "SELECT Geboortedatum FROM Familielid WHERE ID = :familielidId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':familielidId', $familielidId, PDO::PARAM_INT);
        $stmt->execute();
        $geboortedatum = $stmt->fetchColumn();

        // Bereken de leeftijd op basis van het geselecteerde jaar en de geboortedatum
        $geboortejaar = date('Y', strtotime($geboortedatum));
        $leeftijd = $selectedYear - $geboortejaar;

        return $leeftijd; // Retourneer de berekende leeftijd
    }

    // Functie om contributie te berekenen op basis van leeftijd
    private function calculateContribution($leeftijd)
    {
        // Bereken de contributie op basis van de leeftijd
        if ($leeftijd < 8) {
            return 0.5 * 100; // Contributie voor leeftijd onder 8 jaar
        } elseif ($leeftijd >= 8 && $leeftijd < 12) {
            return 0.6 * 100; // Contributie voor leeftijd tussen 8 en 12 jaar
        } elseif ($leeftijd >= 12 && $leeftijd < 18) {
            return 0.75 * 100; // Contributie voor leeftijd tussen 12 en 18 jaar
        } elseif ($leeftijd >= 18 && $leeftijd < 51) {
            return 1 * 100; // Contributie voor leeftijd tussen 18 en 51 jaar
        } else {
            return 0.55 * 100; // Default contributie voor andere leeftijden
        }
    }

    // Methode om een contributie te verwijderen op basis van het contributie-id
    public function deleteContributionById($contributionId)
    {
        // SQL-query om een contributie te verwijderen op basis van het contributie-id
        $query = "DELETE FROM {$this->table} WHERE ID = :contributionId";
        $stmt = $this->conn->prepare($query); // Voorbereiden van de query
        $stmt->bindParam(':contributionId', $contributionId, PDO::PARAM_INT); // Binding van parameter
        return $stmt->execute(); // Uitvoeren van de query en retourneren van het resultaat
    }

    // Methode om een contributie op te halen op basis van het contributie-id
    public function getContributionById($contributionId)
    {
        // SQL-query om een contributie op te halen op basis van het contributie-id
        $query = "SELECT * FROM {$this->table} WHERE ID = :contributionId";
        $stmt = $this->conn->prepare($query); // Voorbereiden van de query
        $stmt->bindParam(':contributionId', $contributionId, PDO::PARAM_INT); // Binding van parameter
        $stmt->execute(); // Uitvoeren van de query
        return $stmt->fetch(PDO::FETCH_ASSOC); // Ophalen van de contributie als een associatieve array
    }

    // Methode om een contributie te bewerken op basis van lidnummer, Boekjaar_id en contributie-id
    public function editContribution($lidnummer, $boekjaarId, $contributieId)
    {
        // Controleer of een combinatie van lidnummer en Boekjaar_id al bestaat
        if ($this->contributionExists($lidnummer, $boekjaarId, $contributieId)) {
            return false; // De combinatie bestaat al, retourneer false
        }

        // Bewerk de contributie
        $query = "UPDATE {$this->table} SET Familielid_id = :lidnummer, Boekjaar_id = :boekjaarId WHERE ID = :contributieId";
        $stmt = $this->conn->prepare($query); // Voorbereiden van de query
        $stmt->bindParam(':lidnummer', $lidnummer, PDO::PARAM_INT); // Binding van parameter
        $stmt->bindParam(':boekjaarId', $boekjaarId, PDO::PARAM_INT); // Binding van parameter
        $stmt->bindParam(':contributieId', $contributieId, PDO::PARAM_INT); // Binding van parameter
        return $stmt->execute(); // Uitvoeren van de query en retourneren van het resultaat
    }

    // Methode om te controleren of een combinatie van lidnummer en Boekjaar_id al bestaat
    private function contributionExists($lidnummer, $boekjaarId, $currentContributieId)
    {
        // SQL-query om te controleren of een combinatie van lidnummer en Boekjaar_id al bestaat, behalve voor de huidige contributie
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE Familielid_id = :lidnummer AND Boekjaar_id = :boekjaarId AND ID != :currentContributieId";
        $stmt = $this->conn->prepare($query); // Voorbereiden van de query
        $stmt->bindParam(':lidnummer', $lidnummer, PDO::PARAM_INT); // Binding van parameter
        $stmt->bindParam(':boekjaarId', $boekjaarId, PDO::PARAM_INT); // Binding van parameter
        $stmt->bindParam(':currentContributieId', $currentContributieId, PDO::PARAM_INT); // Binding van parameter
        $stmt->execute(); // Uitvoeren van de query
        return $stmt->fetchColumn() > 0; // Retourneren van true als de combinatie al bestaat, anders false
    }

    // Methode om alle lidnummers op te halen van de contributies
    public function getAllMemberNumbers()
    {
        // SQL-query om alle unieke lidnummers op te halen van de contributies
        $query = "SELECT DISTINCT Familielid_id FROM Contributie";
        $stmt = $this->conn->prepare($query); // Voorbereiden van de query
        $stmt->execute(); // Uitvoeren van de query
        $lidnummers = $stmt->fetchAll(PDO::FETCH_COLUMN); // Ophalen van alle lidnummers als een array
        return $lidnummers; // Retourneren van de lidnummers
    }

    // Methode om alle Boekjaren op te halen
    public function getAllBoekjaren()
    {
        // SQL-query om alle Boekjaren op te halen
        $query = "SELECT * FROM Boekjaar";
        $stmt = $this->conn->prepare($query); // Voorbereiden van de query
        $stmt->execute(); // Uitvoeren van de query
        $boekjaren = $stmt->fetchAll(PDO::FETCH_ASSOC); // Ophalen van alle Boekjaren als een associatieve array
        return $boekjaren; // Retourneren van de Boekjaren
    }
}
?>
