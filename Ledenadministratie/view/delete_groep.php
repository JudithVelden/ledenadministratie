<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Groep Verwijderen</title>
</head>

<body>
    <?php include_once './view/header.php'; ?>
    <?php include_once './view/nav.php'; ?>
    <main>
        <h1>Groep Verwijderen</h1>
        <form action="index.php?action=deleteGroup" method="post" onsubmit="return confirm('Je gaat nu de groep ' + document.getElementById('groupId').value + ' verwijderen. Wil je verdergaan?');">
            <div>
                <label for="groupId">Selecteer een groep:</label>
                <select name="groupId" id="groupId">
                    <?php foreach ($groups as $group): ?>
                        <option value="<?php echo $group['soortLidID']; ?>"><?php echo $group['soortLidID'] . ' - ' . $group['soortnaam']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <input type="submit" name="submit" value="Verwijderen">
            </div>
        </form>
    </main>
    <?php include_once './view/footer.php'; ?>
</body>

</html>
