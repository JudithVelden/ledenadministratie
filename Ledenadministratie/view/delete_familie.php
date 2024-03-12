<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Familie verwijderen</title>
    <script>
        function confirmDelete(familyName) {
            return confirm("Je gaat familie " + familyName + " verwijderen. Is dat ok?");
        }
    </script>
</head>

<body>
    <?php include_once './view/header.php'; ?>
    <?php include_once './view/nav.php'; ?>
    <main>
        <h1>Familie verwijderen</h1>
        <form method="post" action="index.php?action=deleteFamily" onsubmit="return confirmDelete(document.getElementById('family').options[document.getElementById('family').selectedIndex].text)">
            <label for="family">Selecteer een familie om te verwijderen:</label>
            <select name="family" id="family">
                <?php foreach ($families as $family): ?>
                    <option value="<?php echo $family['ID']; ?>">
                        <?php echo $family['ID'] . ' - ' . $family['Naam']; ?>
                    </option>
                <?php endforeach; ?>
            </select><br>
            <button type="submit" name="delete">Verwijderen</button>
        </form>
    </main>
    <?php include_once './view/footer.php'; ?>
</body>

</html>
