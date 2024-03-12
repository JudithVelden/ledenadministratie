<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leden</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php include_once './view/header.php'; ?>
    <?php include_once './view/nav.php'; ?>

    <main>
        <h1>Leden</h1>
        <a href="index.php?action=addMember">Toevoegen</a>
        <a href="index.php?action=editMember">Aanpassen</a>
        <a href="index.php?action=deleteMember">Verwijderen</a>
        <!-- Tabel met leden -->
        <table>
            <!-- Tabelkoppen -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Voornaam</th>
                    <th>Familienaam</th>
                    <th>Geboortedatum</th>
                    <th>Soort lid</th>
                </tr>
            </thead>
            <tbody>
                <!-- Leden gegevens -->
                <?php foreach ($familiesMetFamilieleden as $familie): ?>
                    <?php foreach ($familie['Familieleden'] as $lid): ?>
                        <tr>
                            <td>
                                <?php echo $lid['ID']; ?>
                            </td>
                            <td>
                                <?php echo $lid['voornaam']; ?>
                            </td>
                            <td>
                                <?php echo $familie['Naam']; ?>
                            </td>
                            <td>
                                <?php echo $lid['Geboortedatum']; ?>
                            </td>
                            <td>
                                <?php echo $lid['soortnaam']; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>


    <?php include_once './view/footer.php'; ?>
</body>

</html>