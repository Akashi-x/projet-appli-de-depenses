<?php
require_once __DIR__ . '/check_session.php';
require_once __DIR__ . '/config/config.php';

// Utiliser l'ID utilisateur depuis la session
$userId = $_SESSION['id'] ?? null;

// Récupérer les informations de l'utilisateur (sécurisé)
$stmt = $mysqlClient->prepare("SELECT NOM_UTILISATEUR, PRENOM FROM utilisateur WHERE ID_UTILISATEUR = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer toutes les catégories avec leur type
$sql = "
    SELECT 
        c.ID_CATEGORIE,
        c.NOM_CATEGORIE,
        t.NOM_TYPE
    FROM categorie c
    JOIN type t ON c.ID_TYPE = t.ID_TYPE
    ORDER BY c.NOM_CATEGORIE
";
$stmt = $mysqlClient->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="CSS/gestion_categorie.css">
    <title>Gestion des Catégories</title>
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
      <li class="active";><a href="gestion_categorie.php" style="text-decoration: none;color:white"><i class="fa-solid fa-house"></i>Gestion Catégories</a></li>
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
      <h1>Gestion des Catégories</h1> 
      <div class="user-profile">
        <a href="profil.php" class="profile-btn">
          <i class="fa-solid fa-user"></i>
        </a>
        <div class="user-dropdown">
          <span class="user-name" onclick="toggleDropdown()"><?= htmlspecialchars($user['NOM_UTILISATEUR'] ?? 'Utilisateur') ?></span>
          <div class="dropdown-menu" id="userDropdown">
            <a href="edit_profil.php"><i class="fa-solid fa-user-edit"></i> Modifier Profil</a>
          </div>
        </div>
      </div>
    </header>

    <section class="introduction">
      <h1>💼 Gestion des Catégories</h1>      
      <p>Suivez et gérez toutes vos catégories en toute simplicité</p>
    </section>
    <div class="separator">
    <section style="width:1100px" class="actions-section">
      <div class="btn-container">
        <a href="ajout_categorie.php" class="btn-ajouter">
          <i class="fa-solid fa-plus"></i> Ajouter une Catégorie
        </a>
      </div>
    </section>
    </div>
    <section class="transactions">
      <h2>Liste des Catégories</h2>

      <div id="liste_categories" class="tab-content">
        <table class="transaction">
          <thead>
            <tr>
              <th>Catégorie</th>
              <th>Type</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($categories)): ?>
  <?php foreach ($categories as $d): ?>
    <?php
      // Couleur selon le type
      $type = $d['NOM_TYPE'] ?? '';
      $couleur = '';

      if (strtolower($type) === 'dépense' || strtolower($type) === 'depense') {
          $couleur = 'color: #ff5555';
      } elseif (strtolower($type) === 'revenu') {
          $couleur = 'color: #55ff55;';
      }
    ?>
    <tr>
      <td><?= htmlspecialchars($d['NOM_CATEGORIE'] ?? 'Non défini') ?></td>
      <td style="<?= $couleur ?>"><?= htmlspecialchars($type) ?></td>
      <td>
        <a href="supp_categorie.php?id=<?= (int)($d['ID_CATEGORIE'] ?? 0) ?>" class="btn-icon btn-supprimer" title="Supprimer" onclick="return confirm('Supprimer cette catégorie ?')">
          <i class="fa-solid fa-trash"></i>
        </a>
      </td>
    </tr>
  <?php endforeach; ?>
<?php else: ?>
  <tr>
    <td colspan="3" style="text-align: center; color: #bbb;">Aucune catégorie trouvée.</td>
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
function toggleDropdown() {
  const el = document.getElementById('userDropdown');
  if (!el) return;
  el.style.display = (el.style.display === 'block') ? 'none' : 'block';
}
</script>

</body>
</html>
