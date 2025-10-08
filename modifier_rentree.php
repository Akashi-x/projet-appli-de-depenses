<?php
require_once "config/config.php";

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $mysqlClient->prepare("SELECT * FROM operation WHERE ID_OPERATIONS_ = ? AND ID_UTILISATEUR = ?");
    $stmt->execute([$id, 5]);
    $revenu = $stmt->fetch(PDO::FETCH_ASSOC);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $desc = $_POST['description'];
    $montant = $_POST['montant'];
    $categorie = $_POST['categorie'];

    $sql = "UPDATE operation SET DATE_OPERATION=?, DESCRIPTION=?, MONTANT=?, ID_CATEGORIE=? 
            WHERE ID_OPERATIONS_=?";
    $stmt = $mysqlClient->prepare($sql);
    $stmt->execute([$date, $desc, $montant, $categorie, $id]);

    header("Location: liste_rentree.php");
    exit;
}
$cats = $mysqlClient->query("SELECT * FROM CATEGORIE")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Revenu</title>
    <link rel="stylesheet" href="CSS/style_rentre.css">
</head>
<body>
    <h2>Modifier un revenu</h2>
    <form method="post">
        <label>Date :</label>
        <input type="date" name="date" value="<?php echo date('Y-m-d');?>" required><br>

        <label>Description :</label>
        <input type="text" name="description" value="<?= htmlspecialchars($revenu['DESCRIPTION']) ?>" required><br>

        <label>Montant :</label>
        <input type="number" name="montant" value="<?= $revenu['MONTANT'] ?>" required><br>

        <label>Catégorie :</label>
        <select name="categorie" required>
            <?php foreach ($cats as $cat): ?>
                <option value="<?= $cat['ID_CATEGORIE'] ?>" <?= ($revenu['ID_CATEGORIE'] == $cat['ID_CATEGORIE']) ? 'selected' : '' ?>>
                    <?= $cat['NOM_CATEGORIE'] ?>
                </option>
            <?php endforeach; ?>
        </select><br>
        <div class="btn-container">
        <button type="submit" class="save-btn">Enregistrer</button>
        <a href="liste_rentree.php"><button type="button">Annuler</button></a>
        </div>
    </form>
</body>
</html>
