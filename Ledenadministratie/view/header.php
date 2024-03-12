<header>
    <h1>LEDENADMINISTRATIE</h1>
    <?php
    // Controleer of de gebruiker is ingelogd
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        // Toon het loginformulier als de gebruiker niet is ingelogd
        echo '
        <div class="login-form">
        <form action="./index.php" method="POST">
                <input type="text" name="username" placeholder="Gebruikersnaam">
                <input type="password" name="password" placeholder="Wachtwoord"><br>
                <input type="submit" name="login" value="Login">
            </form>
        </div>';

    } else {
        // Toon extra opties in de header als de gebruiker is ingelogd
        echo '<p>Je bent succesvol ingelogd</p>';
        echo 
            '<form action="./index.php?logout" method="POST">
                <input type="submit" name="logout" value="Uitloggen">
            </form>';
    }
    ?>
</header>