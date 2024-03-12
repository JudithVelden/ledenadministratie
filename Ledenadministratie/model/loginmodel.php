
<?php

include_once './model/pdoconnectie.php';
class LoginModel {
    // Controleer of de query correct wordt uitgevoerd en of de juiste gegevens worden opgehaald uit de database
    public function loginUser($username, $password, $conn) {
        try {
            // Voer de juiste query uit om de gebruiker te controleren en te zien of het wachtwoord klopt
            $query = "SELECT * FROM login WHERE username = :username";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Controleer of de gebruiker is gevonden
            if ($user) {
                // Controleer of het wachtwoord overeenkomt
                if (password_verify($password, $user['password'])) {
                    // Inloggen succesvol
                    return true;
                } else {
                    // Wachtwoord komt niet overeen
                    return false;
                }
            } else {
                // Gebruiker niet gevonden
                return false;
            }
    } catch (PDOException $e) {
        // Vang PDO-uitzonderingen op
        echo "Fout bij het uitvoeren van de query: " . $e->getMessage();
        return false;
    }
}

}