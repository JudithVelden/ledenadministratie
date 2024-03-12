<?php
// Inclusie van de PDO-verbinding
include_once './model/pdoconnectie.php';

// Definitie van de Familieledenklasse
class Familieleden
{
    private $conn; // PDO-verbinding
    private $table = 'familielid'; // Tabelnaam

    // Constructor om een databaseverbinding tot stand te brengen
    public function __construct()
    {
        $database = new Database(); // Instantiëring van de Databaseklasse
        $this->conn = $database->connect(); // Verbinding maken met de database
    }

    // Methode om alle familieleden van een specifieke familie op te halen
    public function getFamilieleden($familieID)
    {
        // SQL-query om familieleden op te halen met informatie over het soort lid
        $query = 'SELECT fl.*, sl.soortnaam AS soortnaam 
              FROM familielid fl 
              JOIN Soort_lid sl ON fl.Soort_lid_id = sl.soortLidID 
              WHERE Familie_ID = :familieID';
        $stmt = $this->conn->prepare($query); // Voorbereiden van de query
        $stmt->bindParam(':familieID', $familieID); // Binding van parameter
        $stmt->execute(); // Uitvoeren van de query
        return $stmt; // Retourneren van de resultaten
    }

    // Methode om een nieuw familielid toe te voegen aan een familie
    public function addMember($familieID, $voornaam, $geboortedatum, $soortLidID)
    {
        try {
            // Controleer of de familie bestaat
            $queryCheckFamilie = 'SELECT ID FROM familie WHERE ID = :familieID';
            $stmtCheckFamilie = $this->conn->prepare($queryCheckFamilie);
            $stmtCheckFamilie->bindParam(':familieID', $familieID);
            $stmtCheckFamilie->execute();
    
            // Controleer of de familie is gevonden
            if (!$stmtCheckFamilie->fetch()) {
                throw new Exception("Familie niet gevonden.");
            }
    
            // Inputvalidatie
            if (empty($voornaam) || empty($geboortedatum) || empty($soortLidID)) {
                throw new Exception("Voornaam, geboortedatum en soort lid ID mogen niet leeg zijn.");
            }
    
            // Begin een transactie
            $this->conn->beginTransaction();
    
            // Familielid toevoegen
            $query = 'INSERT INTO ' . $this->table . ' (Familie_id, voornaam, Geboortedatum, Soort_lid_id) VALUES (:familieID, :voornaam, :geboortedatum, :soortLidID)';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':familieID', $familieID);
            $stmt->bindParam(':voornaam', $voornaam);
            $stmt->bindParam(':geboortedatum', $geboortedatum);
            $stmt->bindParam(':soortLidID', $soortLidID);
    
            // Voer de query uit
            if ($stmt->execute()) {
                // Commit de transactie als alles goed is gegaan
                $this->conn->commit();
                return true;
            } else {
                // Rol de transactie terug bij een fout
                $this->conn->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            // Vang PDO-fouten op en toon de foutmelding
            $this->conn->rollBack();
            echo "Er is een fout opgetreden: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            // Vang andere fouten op en toon de foutmelding
            $this->conn->rollBack();
            echo "Er is een fout opgetreden: " . $e->getMessage();
            return false;
        }
    }
    

    // Methode om een bestaand familielid te bewerken
    public function editMember($memberID, $newName, $familyID, $newBirthdate)
    {
        try {
            // Inputvalidatie
            if (empty($newName) || empty($familyID) || empty($newBirthdate)) {
                throw new Exception("Voornaam, familie ID en geboortedatum mogen niet leeg zijn.");
            }
    
            // Begin een transactie
            $this->conn->beginTransaction();
    
            // SQL-query om het familielid te bewerken op basis van het lidnummer
            $query = 'UPDATE ' . $this->table . ' SET voornaam = :newName, Familie_ID = :familyID, Geboortedatum = :newBirthdate WHERE ID = :memberID';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':newName', $newName);
            $stmt->bindParam(':familyID', $familyID);
            $stmt->bindParam(':newBirthdate', $newBirthdate);
            $stmt->bindParam(':memberID', $memberID);
    
            if ($stmt->execute()) {
                // Commit de transactie als alles goed is gegaan
                $this->conn->commit();
                return true;
            } else {
                // Rol de transactie terug bij een fout
                $this->conn->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            // Vang PDO-fouten op en toon de foutmelding
            $this->conn->rollBack();
            echo "Er is een fout opgetreden: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            // Vang andere fouten op en toon de foutmelding
            $this->conn->rollBack();
            echo "Er is een fout opgetreden: " . $e->getMessage();
            return false;
        }
    }
    
    // Methode om alle lidnummers op te halen
    public function getAllMemberIDs()
    {
        try {
            $query = 'SELECT ID FROM ' . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Er is een fout opgetreden: " . $e->getMessage();
            return false;
        }
    }
  
    // Methode om een familielid te verwijderen op basis van het lidnummer
    public function deleteMember($memberID)
    {
        try {
            // SQL-query om het familielid te verwijderen op basis van het lidnummer
            $query = 'DELETE FROM ' . $this->table . ' WHERE ID = :memberID';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':memberID', $memberID);
  
            // Voer de query uit
            if ($stmt->execute()) {
                return true; // Geef true terug als het lid succesvol is verwijderd
            }
  
            // Als er een fout optreedt, toon dan de foutmelding
            $errorInfo = $stmt->errorInfo();
            printf("Error: %s.\n", $errorInfo[2]);
            return false; // Geef false terug als er een fout optreedt tijdens het verwijderen
        } catch (PDOException $e) {
            // Vang eventuele PDO-fouten op en toon de foutmelding
            echo "Er is een fout opgetreden: " . $e->getMessage();
            return false; // Geef false terug bij een fout
        }
    }

    // Methode om informatie van een familielid op te halen op basis van het lidnummer
    public function getMemberInfo($memberID)
    {
        try {
            $query = 'SELECT fl.voornaam, fam.Naam AS achternaam 
                      FROM Familielid fl
                      JOIN Familie fam ON fl.Familie_id = fam.ID
                      WHERE fl.ID = :memberID';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':memberID', $memberID);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Er is een fout opgetreden: " . $e->getMessage();
            return false;
        }
    }

    // Methode om alle familieleden op te halen met hun informatie
    public function getAllMemberData()
    {
        try {
            $query = 'SELECT fl.ID, fl.voornaam, fl.Geboortedatum, fam.Naam AS achternaam 
                      FROM Familielid fl
                      JOIN Familie fam ON fl.Familie_id = fam.ID';
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Er is een fout opgetreden: " . $e->getMessage();
            return false;
        }
    }

    // Methode om alle familieleden op te halen
    public function getAllFamilyMembers()
    {
        $query = "SELECT * FROM familielid";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Methode om details van een familie op te halen op basis van het familie-ID
    public function getFamilieDetails($familieID)
    {
        try {
            $query = 'SELECT Geboortedatum FROM ' . $this->table . ' WHERE ID = :familieID';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':familieID', $familieID);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['Geboortedatum'];
        } catch (PDOException $e) {
            echo "Er is een fout opgetreden: " . $e->getMessage();
            return false;
        }
    }

    // Methode om het lidmaatschapstype van een lid op te halen op basis van het lidnummer
    public function getMembershipType($lidID)
    {
        try {
            $query = 'SELECT Soort_lid_id FROM ' . $this->table . ' WHERE ID = :lidID';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':lidID', $lidID);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['Soort_lid_id'];
        } catch (PDOException $e) {
            echo "Er is een fout opgetreden: " . $e->getMessage();
            return false;
        }
    }
}

?>
