<?php
require_once __DIR__ . "/config/config.php";

// Vérifier qu'un ID est fourni
if (!empty($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM operation WHERE ID_OPERATIONS_ = ?");
    $stmt->execute([$id]);
    $operation = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$operation) {
        die("Opération introuvable !");
    }
} else {
    die("ID manquant !");
}

// Charger toutes les catégories
$stmtCat = $pdo->query("SELECT * FROM categorie ORDER BY NOM_CATEGORIE ASC");
$categories = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

// Mise à jour si formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $desc = $_POST['description'];
    $montant = $_POST['montant'];
    $date = $_POST['date_operation'];
    $id_categorie = $_POST['id_categorie'];

    $stmt = $pdo->prepare("
        UPDATE operation 
        SET DESCRIPTION=?, MONTANT=?, DATE_OPERATION=?, ID_CATEGORIE=? 
        WHERE ID_OPERATIONS_=?
    ");
    $stmt->execute([$desc, $montant, $date, $id_categorie, $id]);

    header("Location: mesdepenses.php");
    exit;
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Modifier dépense</title>
  <link rel="stylesheet" href="CSS/editdepenses.css">
</head>
<body>
  <h1 style="text-align:center;">Modifier dépense</h1>

  <form method="post">
    <label>Catégorie :</label>
    <select name="id_categorie" required>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['ID_CATEGORIE'] ?>" 
          <?= ($cat['ID_CATEGORIE'] == $operation['ID_CATEGORIE']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['NOM_CATEGORIE'] ?? '') ?>
        </option>
      <?php endforeach; ?>
    </select>
    <label>Montant :</label>
    <input type="number" name="montant" step="0.01" value="<?= htmlspecialchars($operation['MONTANT'] ?? '') ?>" required>

    <label>Date :</label>
    <input type="date" name="date_operation"
           max="<?= date('Y-m-d') ?>"
           value="<?= htmlspecialchars(date('y-m-d', strtotime($operation['DATE_OPERATION'] ?? ''))) ?>"
           required>

    <label>Description :</label>
    <input type="text" name="description" value="<?= htmlspecialchars($operation['DESCRIPTION'] ?? '') ?>" required>

    
    <?php if ($erreur): ?>
      <div style="color: #a00; font-weight: bold; margin-top: 10px;">
        <?= htmlspecialchars($erreur) ?>
      </div>
    <?php endif; ?>

    <div class="btn-group">
        <button type="submit">Enregistrer</button>
        <button type="button" onclick="window.location.href='mesdepenses.php'">Annuler</button>
    </div>
  </form>
</body>
</html>
