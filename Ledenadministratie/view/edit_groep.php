<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Groep Aanpassen</title>
</head>

<body>
    <?php include_once './view/header.php'; ?>
    <?php include_once './view/nav.php'; ?>
    <main>
        <h1>Groep Aanpassen</h1>
        <form action="index.php?action=editGroup" method="post">
            <div>
                <label for="groupId">Selecteer een groep:</label>
                <select name="groupId" id="groupId">
                    <?php foreach ($groups as $group): ?>
                        <option value="<?php echo $group['soortLidID']; ?>"><?php echo $group['soortLidID'] . ' - ' . $group['soortnaam']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div><br>
            <div>
                <label for="soortnaam">Nieuwe Naam:</label>
                <input type="text" id="soortnaam" name="soortnaam">
            </div><br>
            <div>
                <label for="omschrijving">Nieuwe Omschrijving:</label>
                <input type="text" id="omschrijving" name="omschrijving">
            </div><br>
            <div>
                <label for="korting">Nieuwe Korting:</label>
                <input type="text" id="korting" name="korting">
            </div>
            <div>
                <input type="submit" name="submit" value="Aanpassen">
            </div>
        </form>
    </main>
    <?php include_once './view/footer.php'; ?>
</body>

</html>
