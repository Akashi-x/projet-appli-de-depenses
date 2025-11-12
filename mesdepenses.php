<?php
require_once __DIR__ . '/check_session.php';
require_once __DIR__ . "/config/config.php";

$userId = $_SESSION['id'];

// Récupérer les informations de l'utilisateur
$stmt = $mysqlClient->prepare("SELECT NOM_UTILISATEUR, PRENOM FROM utilisateur WHERE ID_UTILISATEUR = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

//  Dépenses du mois courant
$stmtMois = $mysqlClient->prepare("
    SELECT operation.ID_OPERATIONS_, operation.DATE_OPERATION, operation.DESCRIPTION, operation.MONTANT, categorie.NOM_CATEGORIE
    FROM operation
    LEFT JOIN categorie ON operation.ID_CATEGORIE = categorie.ID_CATEGORIE
    WHERE operation.ID_UTILISATEUR = ? 
      AND MONTH(DATE_OPERATION) = MONTH(CURRENT_DATE())
      AND YEAR(DATE_OPERATION) = YEAR(CURRENT_DATE())
    ORDER BY DATE_OPERATION DESC
");
$stmtMois->execute([$userId]);
$depensesMois = $stmtMois->fetchAll(PDO::FETCH_ASSOC);

// Toutes les dépenses
$stmt = $mysqlClient->prepare("
    SELECT operation.ID_OPERATIONS_, operation.DATE_OPERATION, operation.DESCRIPTION, operation.MONTANT, categorie.NOM_CATEGORIE
    FROM operation
    LEFT JOIN categorie ON operation.ID_CATEGORIE = categorie.ID_CATEGORIE
    WHERE operation.ID_UTILISATEUR = ?
    ORDER BY operation.DATE_OPERATION DESC
");
$stmt->execute([$userId]);
$depenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Liste des Dépenses</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<link rel="stylesheet" href="CSS/depenses.css">
<link rel="stylesheet" href="CSS/accueil.css">
<link rel="stylesheet" href="CSS/sidebar.css">

</head>
<body>
<div class="container">
  <!-- Sidebar -->
  <aside class="sidebar" style="font-size: 17px;">
        <div class="titre" style="font-weight: bold;">
      <h2>💰 Suivi Dépenses</h2>
      <h1>SAMA KALPE</h1>
      </div>
      <ul>
        <li> <a href="accueil.php" style="text-decoration: none;color:white"><i class="fa-solid fa-house"></i> Accueil</a></li>
        <li><a href="liste_rentree.php" style="text-decoration: none;color:white"><i class="fa-solid fa-wallet"></i> Revenus</a></li>
        <li class="active"><a href="mesdepenses.php" style="text-decoration: none;color:white"><i class="fa-solid fa-credit-card"></i> Dépenses</a></li>
        <li><a href="activite.php" style="text-decoration: none;color:white"><i class="fa-solid fa-chart-pie"></i> Activité</a></li>
      </ul>
      <div class="sidebar-footer">
        <a href="deconnexion.php" class="logout-sidebar">
          <i class="fa-solid fa-sign-out-alt"></i> Déconnexion
        </a>
      </div>
    </aside>
  <!-- Main Content -->
  <main class="main-content">
    <h1>MES DEPENSES 💸💸</h1>

    <!-- Bouton Ajouter centré -->
    <div class="btn-container">
        <a href="page-ajout.php" class="btn-ajouter">+ Ajouter</a>
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
                    <a href="modifoperation.php?id=<?= $d['ID_OPERATIONS_'] ?>" class="btn btn-modifier">Modifier</a>
                    <a href="delete_operation.php?id=<?= $d['ID_OPERATIONS_'] ?>" class="btn btn-supprimer">Supprimer</a>
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
                    <a href="mofifoperation.php?id=<?= $d['ID_OPERATIONS_'] ?>" class="btn btn-modifier">Modifier</a>
                    <a href="delete_operation.php?id=<?= $d['ID_OPERATIONS_'] ?>" class="btn btn-supprimer">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
  </main>
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