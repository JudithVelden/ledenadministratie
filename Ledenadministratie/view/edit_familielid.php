<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Familielid aanpassen</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php include_once './view/header.php'; ?>
    <?php include_once './view/nav.php'; ?>
    
    <main>
        <h1>Familielid aanpassen</h1>
        <form action="index.php?action=editMember" method="post">
            <label for="memberID">Wat is je lidnummer?</label>
            <input type="text" id="memberID" name="memberID"><br><br>
            
            <label for="newName">Nieuwe naam:</label>
            <input type="text" id="newName" name="newName"><br><br>

            <label for="familyID">Selecteer familie:</label>
            <select id="familyID" name="familyID">
                <?php foreach ($families as $family): ?>
                    <option value="<?php echo $family['ID']; ?>"><?php echo $family['Naam']; ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="newBirthdate">Nieuwe geboortedatum:</label>
            <input type="date" id="newBirthdate" name="newBirthdate"><br><br>

            <input type="submit" name="submit" value="Aanpassen">
        </form>
    </main>
    
    <?php include_once './view/footer.php'; ?>
</body>
</html>
