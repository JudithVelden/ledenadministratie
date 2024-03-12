<?php
class Database
{
    // Database parameters
    private $host = 'localhost';  // Hostnaam van de database-server
    private $db_name = 'ledenadministratie';  // Naam van de database
    private $username = 'admin';  // Gebruikersnaam voor de database
    private $password = 'admin';  // Wachtwoord voor de database
    private $conn;  // PDO-verbinding

    // Methode om verbinding te maken met de database
    public function connect()
    {
        $this->conn = null;

        try {
            // Maak een nieuwe PDO-verbinding met de opgegeven parameters
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );

            // Zet de attribuutwaarde voor foutafhandeling in op ERRMODE_EXCEPTION
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Vang en toon eventuele fouten die optreden bij het maken van de verbinding
            echo 'Connection Error: ' . $e->getMessage();
        }

        // Retourneer de gemaakte verbinding of null als er een fout is opgetreden
        return $this->conn;
    }
}
?>
