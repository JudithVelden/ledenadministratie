<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Boekjaar Verwijderen</title>
</head>

<body>
    <?php include_once './view/header.php'; ?>
    <?php include_once './view/nav.php'; ?>

    <main>
        <h1>Boekjaar Verwijderen</h1>
        <form method="post" action="index.php?action=deleteYear">
            <label for="jaar">Selecteer het boekjaar dat je wil verwijderen:</label>
            <select id="jaar" name="jaar">
                <?php foreach ($boekjaren as $boekjaar): ?>
                    <option value="<?php echo $boekjaar['boekjaar_id']; ?>"><?php echo $boekjaar['boekjaar']; ?></option>
                <?php endforeach; ?>
            </select><br><br>
            <input type="submit" name="submit" value="Verwijderen">
        </form>
    </main>

    <?php include_once './view/footer.php'; ?>
</body>

</html>
