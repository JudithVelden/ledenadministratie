<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Groepen</title>
</head>

<body>
    <?php include_once './view/header.php'; ?>
    <?php include_once './view/nav.php'; ?>
    <main>
        <h1>Groepen</h1>
        <a href="index.php?action=addGroup">Toevoegen</a>
        <a href="index.php?action=editGroup">Aanpassen</a>
        <a href="index.php?action=deleteGroup">Verwijderen</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Naam</th>
                    <th>Omschrijving</th>
                    <th>Korting</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($groups as $group): ?>
                    <tr>
                        <td>
                            <?php echo $group['soortLidID']; ?>
                        </td>
                        <td>
                            <?php echo $group['soortnaam']; ?>
                        </td>
                        <td>
                            <?php echo $group['Omschrijving']; ?>
                        </td>
                        <td>
                            <?php $korting = number_format($group['korting'], 0, ',', '') . '%';
                            echo $korting; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <?php include_once './view/footer.php'; ?>
</body>

</html>