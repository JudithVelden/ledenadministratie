<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Boekjaren overzicht</title>
</head>

<body>
    <?php include_once './view/header.php'; ?>
    <?php include_once './view/nav.php'; ?>

    <main>
        <h1>Boekjaren Overzicht</h1>
        <a href="index.php?action=addYear">Toevoegen</a>
        <a href="index.php?action=editYear">Aanpassen</a>
        <a href="index.php?action=deleteYear">Verwijderen</a>
        <table>
            <thead>
                <tr>
                    <th>Boekjaar ID</th>
                    <th>Boekjaar</th>
                    <th>Aantal leden in boekjaar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($boekjaren as $boekjaar): ?>
                    <tr>
                        <td>
                            <?php echo $boekjaar['boekjaar_id']; ?>
                        </td>
                        <td>
                            <?php echo $boekjaar['boekjaar']; ?>
                        </td>
                        <td>
                            <?php echo $boekjaar['aantal_leden']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <?php include_once './view/footer.php'; ?>
</body>

</html>