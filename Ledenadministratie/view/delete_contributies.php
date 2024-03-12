<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contributie verwijderen</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php include_once './view/header.php'; ?>
    <?php include_once './view/nav.php'; ?>

    <main>
    <h1>Contributie Verwijderen</h1>
    <form method="POST">
        <label for="selectedContributionId">Selecteer een contributie om te verwijderen:</label>
        <select name="selectedContributionId" id="selectedContributionId">
            <?php foreach ($contributions as $contribution): ?>
                <option value="<?php echo $contribution['contributie_id']; ?>">
                    <?php echo $contribution['contributie_id']; ?>
                </option>
            <?php endforeach; ?>
        </select><br>
        <button type="submit" name="submit">Verwijderen</button>
    </form>
    </main>

    <?php include_once './view/footer.php'; ?>
</body>

</html>
