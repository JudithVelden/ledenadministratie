<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Contributies Toevoegen</title>
</head>

<body>
    <?php include_once './view/header.php'; ?>
    <?php include_once './view/nav.php'; ?>
    <main>
        <h1>Contributies Toevoegen</h1>
        <form action="index.php?action=addcontributions" method="POST">
            <label for="lidID">Lid:</label>
            <select id="lidID" name="lidID" required>
                <?php foreach ($familielidData as $familielid): ?>
                    <option value="<?php echo $familielid['ID']; ?>">
                        <?php echo $familielid['ID'] . " - " . $familielid['voornaam'] . " " . $familielid['achternaam']; ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="boekjaar">Boekjaar:</label>
            <select id="boekjaar" name="selectedYear" required>
                <?php foreach ($years as $year): ?>
                    <option value="<?php echo $year['year_id']; ?>">
                        <?php echo $year['year_id']; ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <input type="submit" name="submit" value="Toevoegen">
        </form>
    </main>
    <?php include_once './view/footer.php'; ?>
</body>

</html>