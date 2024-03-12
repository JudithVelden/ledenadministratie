<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Boekjaar Aanpassen</title>
</head>

<body>
    <?php include_once './view/header.php'; ?>
    <?php include_once './view/nav.php'; ?>

    <main>
    <h1>Boekjaar Aanpassen</h1>
    <?php if ($error): ?>
        <p class="error-message"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="post" action="index.php?action=editYear">
        <label for="jaar">Selecteer de ID van het boekjaar dat je wil aanpassen:</label>
        <select id="jaar" name="jaar">
            <?php foreach ($boekjaren as $boekjaar): ?>
                <option value="<?php echo $boekjaar['boekjaar_id']; ?>"><?php echo $boekjaar['boekjaar_id']; ?></option>
            <?php endforeach; ?>
        </select><br><br>
        <label for="nieuw_jaar">Welk jaar wil je aan de ID koppelen?</label>
        <input type="text" id="nieuw_jaar" name="nieuw_jaar"><br><br>
        <input type="submit" name="submit" value="Aanpassen">
    </form>
</main>


    <?php include_once './view/footer.php'; ?>
</body>

</html>
