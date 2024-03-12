<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Groep Toevoegen</title>
</head>

<body>
    <?php include_once './view/header.php'; ?>
    <?php include_once './view/nav.php'; ?>
    <main>
        <h1>Groep Toevoegen</h1>
        <form action="" method="POST">
            <label for="naam">Naam:</label>
            <input type="text" id="soortnaam" name="soortnaam"><br><br>
            <label for="omschrijving">Omschrijving:</label>
            <input type="text" id="omschrijving" name="omschrijving"><br><br>
            <label for="korting">Korting:</label>
            <input type="text" id="korting" name="korting"><br><br>
            <input type="submit" name="submit" value="Toevoegen">
        </form>
    </main>
    <?php include_once './view/footer.php'; ?>
</body>

</html>
