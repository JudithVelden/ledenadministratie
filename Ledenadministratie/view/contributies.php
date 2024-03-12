<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Contributies</title>
</head>

<body>
    <?php include_once './view/header.php'; ?>
    <?php include_once './view/nav.php'; ?>
    <main>
        <h1>Contributies</h1>
        <a href="index.php?action=addcontributions">Toevoegen</a>
        <a href="index.php?action=editcontributions">Aanpassen</a>
        <a href="index.php?action=deletecontributions">Verwijderen</a>
        <table>
            <thead>
                <tr>
                    <th>Contributie ID</th>
                    <th>Voornaam</th>
                    <th>Achternaam</th>
                    <th>lidnummer</th>
                    <th>Bedrag</th>
                    <th>Boekjaar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contributions as $contribution): ?>
                    <tr>
                        <td><?php echo $contribution['contributie_id']; ?></td>
                        <td><?php echo $contribution['voornaam']; ?></td>
                        <td><?php echo $contribution['achternaam']; ?></td>
                        <td><?php echo $contribution['familielid_id']; ?></td>
                        <td>&euro;<?php echo $contribution['Bedrag']; ?></td>
                        <td><?php echo $contribution['boekjaar']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <?php include_once './view/footer.php'; ?>
</body>
</html>