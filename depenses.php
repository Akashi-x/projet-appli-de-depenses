<?php
require_once __DIR__ . '/check_session.php';
require_once __DIR__ . "/config/config.php";

// Utiliser l'ID utilisateur depuis la session
$userId = $_SESSION['id'];

// Récupérer les informations de l'utilisateur
$stmt = $mysqlClient->prepare("SELECT NOM_UTILISATEUR, PRENOM FROM utilisateur WHERE ID_UTILISATEUR = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

try {
    // Vérifier le filtre dans l'URL
    $filtre = isset($_GET['filtre']) ? $_GET['filtre'] : 'mois_courant';

    if ($filtre === 'mois_courant') {
        // Dépenses du mois courant
        $sql = "SELECT operation.ID_OPERATIONS_, operation.DATE_OPERATION, operation.DESCRIPTION, operation.MONTANT, categorie.NOM_CATEGORIE
                FROM operation
                LEFT JOIN categorie ON operation.ID_CATEGORIE = categorie.ID_CATEGORIE
                LEFT JOIN type ON categorie.ID_TYPE = type.ID_TYPE
                WHERE operation.ID_UTILISATEUR = ? 
                  AND type.NOM_TYPE = 'Depense'
                  AND MONTH(DATE_OPERATION) = MONTH(CURRENT_DATE())
                  AND YEAR(DATE_OPERATION) = YEAR(CURRENT_DATE())
                ORDER BY DATE_OPERATION DESC";
    } else {
        // Toutes les dépenses
        $sql = "SELECT operation.ID_OPERATIONS_, operation.DATE_OPERATION, operation.DESCRIPTION, operation.MONTANT, categorie.NOM_CATEGORIE
                FROM operation
                LEFT JOIN categorie ON operation.ID_CATEGORIE = categorie.ID_CATEGORIE
                LEFT JOIN type ON categorie.ID_TYPE = type.ID_TYPE
                WHERE operation.ID_UTILISATEUR = ?
                  AND type.NOM_TYPE = 'Depense'
                ORDER BY operation.DATE_OPERATION DESC";
    }

    $stmt = $mysqlClient->prepare($sql);
    $stmt->execute([$userId]);
    $depenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer aussi toutes les dépenses pour l'onglet "Tous"
    $sqlTous = "SELECT operation.ID_OPERATIONS_, operation.DATE_OPERATION, operation.DESCRIPTION, operation.MONTANT, categorie.NOM_CATEGORIE
                FROM operation
                LEFT JOIN categorie ON operation.ID_CATEGORIE = categorie.ID_CATEGORIE
                LEFT JOIN type ON categorie.ID_TYPE = type.ID_TYPE
                WHERE operation.ID_UTILISATEUR = ?
                  AND type.NOM_TYPE = 'Depense'
                ORDER BY operation.DATE_OPERATION DESC";
    
    $stmtTous = $mysqlClient->prepare($sqlTous);
    $stmtTous->execute([$userId]);
    $depensesTous = $stmtTous->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/accueil.css">
    <link rel="stylesheet" href="CSS/dropdown.css">
    <link rel="stylesheet" href="CSS/sidebar.css">
    <link rel="stylesheet" href="CSS/pages-actions.css">
    <title>Mes Dépenses</title>
</head>
<body>
<div class="container">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="titre">
      <a href="accueil.php"><img src="icone/logo.png" alt="logo" class="logo" style="cursor: pointer;"></a>
      <h1>SAMA KALPE</h1>
    </div>

    <ul>
      <li><a href="accueil.php" style="text-decoration: none;color:white"><i class="fa-solid fa-house"></i> Accueil</a></li>
      <li><a href="revenus.php" style="text-decoration: none;color:white"><i class="fa-solid fa-wallet"></i> Revenus</a></li>
      <li class="active"><a href="depenses.php" style="text-decoration: none;color:white"><i class="fa-solid fa-credit-card"></i> Dépenses</a></li>
      <li><a href="activite.php" style="text-decoration: none;color:white"><i class="fa-solid fa-chart-pie"></i> Activité</a></li>
    </ul>

    <div class="sidebar-footer">
      <a href="deconnexion.php" class="logout-sidebar">
        <i class="fa-solid fa-sign-out-alt"></i> Déconnexion
      </a>
    </div>
  </aside>

  <!-- Main -->
  <main class="main">
    
    <header class="header">
      <h1>Dépenses</h1> 
      <div class="user-profile">
        <a href="profil.php" class="profile-btn">
          <i class="fa-solid fa-user"></i>
        </a>
        <div class="user-dropdown">
          <span class="user-name" onclick="toggleDropdown()"><?php echo $user['NOM_UTILISATEUR']; ?></span>
          <div class="dropdown-menu" id="userDropdown">
            <a href="edit_profil.php"><i class="fa-solid fa-user-edit"></i> Modifier Profil</a>
          </div>
        </div>
      </div>
    </header>

    <section class="introduction">
      <h1>💸 Gestion des Dépenses</h1>      
      <p>Suivez et gérez toutes vos dépenses en toute simplicité</p>
    </section>

    <section class="actions-section">
      <div class="btn-container">
        <a href="ajout_depense.php" class="btn-ajouter">
          <i class="fa-solid fa-plus"></i> Ajouter une Dépense
        </a>
      </div>
    </section>

    <section class="filters-section">
      <div class="tabs">
        <button class="tab-btn <?= ($filtre === 'mois_courant') ? 'active' : '' ?>" onclick="showTab('mois_courant')">
          <i class="fa-solid fa-calendar-month"></i> Depense du mois 
        </button>
        <button class="tab-btn <?= ($filtre === 'tous') ? 'active' : '' ?>" onclick="showTab('tous')">
          <i class="fa-solid fa-list"></i> Toutes les dépenses
        </button>
      </div>
    </section>

    <section class="transactions">
      <h2>Liste des Dépenses</h2>
      
      <div id="mois_courant" class="tab-content" <?= ($filtre === 'tous') ? 'style="display:none"' : '' ?>>
        <table class="transaction">
          <thead>
            <tr>
              <th>Catégorie</th>
              <th>Description</th>
              <th>Date</th>
              <th>Montant</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($filtre === 'mois_courant' && !empty($depenses)): ?>
              <?php foreach ($depenses as $d): ?>
                <tr>
                  <td><?= htmlspecialchars($d['NOM_CATEGORIE'] ?? 'Non défini') ?></td>
                  <td><?= htmlspecialchars($d['DESCRIPTION'] ?? '') ?></td>
                  <td><?= htmlspecialchars($d['DATE_OPERATION'] ?? '') ?></td>
                  <td class="montant negatif">-<?= number_format($d['MONTANT'] ?? 0, 0, ',', ' ') ?> FCFA</td>
                  <td>
                    <a href="modif_depense.php?id=<?= $d['ID_OPERATIONS_'] ?>" class="btn-icon btn-modifier">
                      <i class="fa-solid fa-edit"></i>
                    </a>
                    <a href="supp_depense.php?id=<?= $d['ID_OPERATIONS_'] ?>" class="btn-icon btn-supprimer" onclick="return confirm('Supprimer cette dépense ?')">
                      <i class="fa-solid fa-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" style="text-align: center; color: #bbb;">Aucune dépense trouvée pour ce mois.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <div id="tous" class="tab-content" <?= ($filtre === 'mois_courant') ? 'style="display:none"' : '' ?>>
        <table class="transaction">
          <thead>
            <tr>
              <th>Catégorie</th>
              <th>Description</th>
              <th>Date</th>
              <th>Montant</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($filtre === 'tous' && !empty($depensesTous)): ?>
              <?php foreach ($depensesTous as $d): ?>
                <tr>
                  <td><?= htmlspecialchars($d['NOM_CATEGORIE'] ?? 'Non défini') ?></td>
                  <td><?= htmlspecialchars($d['DESCRIPTION'] ?? '') ?></td>
                  <td><?= htmlspecialchars($d['DATE_OPERATION'] ?? '') ?></td>
                  <td class="montant negatif">-<?= number_format($d['MONTANT'] ?? 0, 0, ',', ' ') ?> FCFA</td>
                  <td>
                    <a href="modif_depense.php?id=<?= $d['ID_OPERATIONS_'] ?>" class="btn-icon btn-modifier">
                      <i class="fa-solid fa-edit"></i>
                    </a>
                    <a href="supp_depense.php?id=<?= $d['ID_OPERATIONS_'] ?>" class="btn-icon btn-supprimer" onclick="return confirm('Supprimer cette dépense ?')">
                      <i class="fa-solid fa-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" style="text-align: center; color: #bbb;">Aucune dépense trouvée.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </section>
  </main>
</div>

<script src="JS/dropdown.js"></script>
<script>
function showTab(id) {
    const url = new URL(window.location);
    url.searchParams.set('filtre', id === 'mois_courant' ? 'mois_courant' : 'tous');
    window.location.href = url.toString();
}
</script>

</body>
</html>
