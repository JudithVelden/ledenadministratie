<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Families</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php include_once './view/header.php'; ?>
    <?php include_once './view/nav.php'; ?>

    <main>
        <h1>Families</h1>
        <!-- Knoppen voor toevoegen, verwijderen en aanpassen -->
        <a href="index.php?action=addFamily">Toevoegen</a>
        <a href="index.php?action=editFamily">Aanpassen</a>
        <a href="index.php?action=deleteFamily">Verwijderen</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Familienaam</th>
                    <th>Familieleden</th>
                    <th>Adres</th>
                    <th>Contributie 2024</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($familiesMetFamilieleden as $familie): ?>
                    <tr>
                        <td>
                            <?php echo $familie['ID']; ?>
                        </td>
                        <td>
                            <?php echo $familie['Naam']; ?>
                        </td>
                        <td>
                            <?php foreach ($familie['familieleden'] as $familielid): ?>
                                <?php echo $familielid['voornaam'] . '<br>'; ?>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <?php echo $familie['Adres']; ?>
                        </td>
                        <td><?php echo $familie['totalContribution']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </main>

    <?php include_once './view/footer.php'; ?>
</body>

</html>