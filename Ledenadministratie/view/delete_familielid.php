<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Familielid verwijderen</title>
</head>

<body>
    <?php include_once './view/header.php'; ?>
    <?php include_once './view/nav.php'; ?>

    <script>
        function confirmDelete() {
            var memberID = document.querySelector('select[name="member"]').value;
            var memberName = document.querySelector('select[name="member"] option:checked').text;
            var confirmation = confirm("Let op: je gaat " + memberName + " verwijderen. Is dat ok?");
            return confirmation;
        }
    </script>

    <main>
        <h2>Familielid verwijderen</h2>
        <form method="POST" onsubmit="return confirmDelete()">
            <select name="member">
                <?php foreach ($familielidData as $familielid): ?>
                    <option value="<?php echo $familielid['ID']; ?>">
                        <?php echo $familielid['ID'] . " - " . $familielid['voornaam'] . " " . $familielid['achternaam']; ?>
                    </option>
                <?php endforeach; ?>
            </select><br>
            <button type="submit" name="delete">Verwijderen</button>
        </form>
    </main>

    <?php include_once './view/footer.php'; ?>
</body>

</html>
