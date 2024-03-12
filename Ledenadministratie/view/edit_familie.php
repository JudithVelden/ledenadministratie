<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Familie aanpassen</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php include_once './view/header.php'; ?>
    <?php include_once './view/nav.php'; ?>

    <main>
        <h1>Familie aanpassen</h1>
        <form action="index.php?action=editFamily" method="post">
            <label for="family">Selecteer familie:</label>
            <select name="familyID" id="family">
                <?php foreach ($familiesMetFamilieleden as $familie): ?>
                    <option value="<?php echo $familie['ID']; ?>"><?php echo $familie['Naam']; ?></option>
                <?php endforeach; ?>
            </select><br><br>
            <label for="newName">Nieuwe familienaam:</label>
            <input type="text" id="newName" name="newName"><br><br>
            <label for="newAddress">Nieuw adres:</label>
            <input type="text" id="newAddress" name="newAddress"><br>
            <button type="submit" name="submit">Aanpassen</button>
        </form>
    </main>

    <?php include_once './view/footer.php'; ?>
</body>
</html>
