<?php
require_once "config/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $desc = $_POST['description'];
    $montant = $_POST['montant'];
    $categorie = $_POST['categorie'];

    $sql = "INSERT INTO operation (ID_UTILISATEUR, DATE_OPERATION, DESCRIPTION, MONTANT, ID_CATEGORIE) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqlClient->prepare($sql);
    $stmt->execute([5, $date, $desc, $montant, $categorie]);

    header("Location: liste_rentree.php");
    exit;
}
$cats = $mysqlClient->query("SELECT * FROM CATEGORIE")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter Revenu</title>
    <link rel="stylesheet" href="CSS/style_rentre.css">
</head>
<body>
    <h2>Ajouter un revenu</h2>
    <form method="post">
        <label>Date :</label>
        <input type="date" name="date" value="<?php echo date('Y-m-d');?>" required><br>

        <label>Description :</label>
        <input type="text" name="description" required><br>

        <label>Montant :</label>
        <input type="number" name="montant" required><br>

        <label>Catégorie :</label>
        <select name="categorie" required>
            <?php foreach ($cats as $cat): ?>
                <option value="<?= $cat['ID_CATEGORIE'] ?>"><?= $cat['NOM_CATEGORIE'] ?></option>
            <?php endforeach; ?>
        </select><br>
        <div class="btn-container">
        <button type="submit">Enregistrer</button>
        <a href="liste_rentree.php"><button type="button" >Annuler</button></a>
        </div>
    </form>
</body>
</html>
