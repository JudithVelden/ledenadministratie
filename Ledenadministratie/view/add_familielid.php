<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuw familielid toevoegen</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <main>
        <h1>Nieuw familielid toevoegen</h1>
        <form method="post" action="">
    <label for="familieID">Familie:</label>
    <select id="familieID" name="familieID">
        <?php foreach ($families as $familie): ?>
            <option value="<?php echo $familie['ID']; ?>">
                <?php echo $familie['Naam']; ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>
    <label for="voornaam">Voornaam:</label>
    <input type="text" id="voornaam" name="voornaam" required><br><br>
    <label for="geboortedatum">Geboortedatum:</label>
    <input type="date" id="geboortedatum" name="geboortedatum" required><br>
    <input type="submit" name="submit" value="Toevoegen">
</form>



    </main>
</body>

</html>