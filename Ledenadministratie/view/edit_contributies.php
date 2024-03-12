<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Contributie Aanpassen</title>
</head>

<body>
    <main>
    <h1>Contributie Aanpassen</h1>
    <?php if (!empty($errorMessage)): ?>
        <p><?php echo $errorMessage; ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="selectedContributionId">Selecteer een contributie om te bewerken:</label>
        <select name="selectedContributionId" id="selectedContributionId">
            <?php foreach ($contributions as $contribution): ?>
                <option value="<?php echo $contribution['contributie_id']; ?>">
                    <?php echo $contribution['contributie_id']; ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        <label for="lidnummer">Selecteer een lidnummer:</label>
        <select name="lidnummer" id="lidnummer">
            <?php foreach ($lidnummers as $lidnummer): ?>
                <option value="<?php echo $lidnummer; ?>">
                    <?php echo $lidnummer; ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        <label for="boekjaarId">Selecteer een boekjaar:</label>
        <select name="boekjaarId" id="boekjaarId">
            <?php foreach ($boekjaren as $boekjaar): ?>
                <option value="<?php echo $boekjaar['ID']; ?>">
                    <?php echo $boekjaar['Jaar']; ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        <button type="submit" name="submit">Aanpassen</button>
    </form>
            </main>
</body>

</html>
