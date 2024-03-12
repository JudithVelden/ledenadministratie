<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Boekjaar Toevoegen</title>
</head>

<body>
    <?php include_once './view/header.php'; ?>
    <?php include_once './view/nav.php'; ?>

    <main>
    <h1>Boekjaar Toevoegen</h1>
    <?php if (!empty($errorMessage)) : ?>
        <p><?php echo $errorMessage; ?></p>
    <?php endif; ?>
    <form method="post" action="index.php?action=addYear">
        <label for="jaar">Jaar:</label>
        <input type="text" id="jaar" name="jaar"><br>
        <input type="submit" name="submit" value="Toevoegen">
    </form>
</main>

    <?php include_once './view/footer.php'; ?>
</body>

</html>
