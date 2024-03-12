<?php
session_start();

// Laad de controller
include_once 'controller/controller.php';
include_once 'model/pdoconnectie.php';


// Maak een instantie van Database om de verbinding te verkrijgen
$database = new Database();
$conn = $database->connect();

// Maak een instantie van de FamilieController en geef de databaseverbinding door
$familieController = new FamilieController($conn);

// Controleer of er een loginformulier is verzonden
if(isset($_POST['login'])) {
    $familieController->login();
}

if (isset($_GET['logout'])) {
    $familieController->logout();
}

// Controleer of er een foutmelding is ingesteld
if(isset($_SESSION['login_error'])) {
    // Toon de foutmelding
    echo '<p class="error">' . $_SESSION['login_error'] . '</p>';
    // Verwijder de foutmelding uit de sessie
    unset($_SESSION['login_error']);
}

?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ledenadministratie</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <?php include_once './view/header.php'; ?>
    <?php include_once './view/nav.php'; ?>
    <?php 
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        // De gebruiker is ingelogd, laad de main.php
        include_once './view/main.php';
    } else {
        // Controleer of er een foutmelding is ingesteld
        if(isset($_SESSION['login_error'])) {
            // Toon de foutmelding
            echo '<p class="error">' . $_SESSION['login_error'] . '</p>';
            // Verwijder de foutmelding uit de sessie
            unset($_SESSION['login_error']);
        } else {
            // Toon de boodschap dat de gebruiker moet inloggen om toegang te krijgen tot de hoofdinhoud
            echo '<p class="inlogtekst">Je moet eerst inloggen om toegang te krijgen tot de hoofdinhoud.</p>';
        }
    }
    ?>
    <?php include_once './view/footer.php'; ?>

</body>

</html>