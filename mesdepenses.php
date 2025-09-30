<?php
require_once __DIR__ . "/config/config.php";

//  Dépenses du mois courant
$stmtMois = $pdo->prepare("
    SELECT operation.ID_OPERATIONS_, operation.DATE_OPERATION, operation.DESCRIPTION, operation.MONTANT, categorie.NOM_CATEGORIE
    FROM operation
    LEFT JOIN categorie ON operation.ID_CATEGORIE = categorie.ID_CATEGORIE
    WHERE MONTH(DATE_OPERATION) = MONTH(CURRENT_DATE())
      AND YEAR(DATE_OPERATION) = YEAR(CURRENT_DATE())
    ORDER BY DATE_OPERATION DESC
");
$stmtMois->execute();
$depensesMois = $stmtMois->fetchAll(PDO::FETCH_ASSOC);

// Toutes les dépenses
$stmt = $pdo->prepare("
    SELECT operation.ID_OPERATIONS_, operation.DATE_OPERATION, operation.DESCRIPTION, operation.MONTANT, categorie.NOM_CATEGORIE
    FROM operation
    LEFT JOIN categorie ON operation.ID_CATEGORIE = categorie.ID_CATEGORIE
    ORDER BY operation.DATE_OPERATION DESC
");
$stmt->execute();
$depenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Liste des Dépenses</title>
<link rel="stylesheet" href="CSS\depenses.css">
</head>
<body>
<div class="container">
    <h1>MES DEPENSES 💸💸</h1>

    <!-- Bouton Ajouter centré -->
    <div class="btn-container">
        <a href="" class="btn-ajouter">+ Ajouter</a>
    </div>

    <!-- Onglets -->
    <div class="tabs">
        <button class="tab-btn" onclick="showTab('mois_courant')">Dépenses du mois courant</button>
        <button class="tab-btn" onclick="showTab('toutes')">Toutes les dépenses</button>
    </div>

    <!-- Contenu Dépenses du mois courant -->
    <div id="mois_courant" class="tab-content">
        <table>
            <tr>
                <th>Date</th>
                <th>Catégorie</th>
                <th>Description</th>
                <th>Montant</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($depensesMois as $d): ?>
            <tr>
                <td><?= htmlspecialchars($d['DATE_OPERATION'] ?? '') ?></td>
                <td><?= htmlspecialchars($d['NOM_CATEGORIE'] ?? 'Non défini') ?></td>
                <td><?= htmlspecialchars($d['DESCRIPTION'] ?? '') ?></td>
                <td class="montant">-<?= htmlspecialchars($d['MONTANT'] ?? '') ?> CFA</td>
                <td>
                    <a href="edit_operation.php?id=<?= $d['ID_OPERATIONS_'] ?>" class="btn btn-modifier">Modifier</a>
                    <a href="modifoperation.php?id=<?= $d['ID_OPERATIONS_'] ?>" class="btn btn-supprimer">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Contenu Toutes les dépenses -->
    <div id="toutes" class="tab-content" style="display:none">
        <table>
            <tr>
                <th>Date</th>
                <th>Catégorie</th>
                <th>Description</th>
                <th>Montant</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($depenses as $d): ?>
            <tr>
                <td><?= htmlspecialchars($d['DATE_OPERATION'] ?? '') ?></td>
                <td><?= htmlspecialchars($d['NOM_CATEGORIE'] ?? 'Non défini') ?></td>
                <td><?= htmlspecialchars($d['DESCRIPTION'] ?? '') ?></td>
                <td class="montant">-<?= htmlspecialchars($d['MONTANT'] ?? '') ?> CFA</td>
                <td>
                    <a href="edit_operation.php?id=<?= $d['ID_OPERATIONS_'] ?>" class="btn btn-modifier">Modifier</a>
                    <a href="delete_operation.php?id=<?= $d['ID_OPERATIONS_'] ?>" class="btn btn-supprimer">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<script>
function showTab(id) {
    document.getElementById('mois_courant').style.display = 'none';
    document.getElementById('toutes').style.display = 'none';
    document.getElementById(id).style.display = 'block';
}
</script>
</body>
</html>
