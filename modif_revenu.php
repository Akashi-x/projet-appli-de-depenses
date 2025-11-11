<?php
require_once __DIR__ . '/check_session.php';
require_once __DIR__ . '/config/config.php';

$message = '';
$userId = $_SESSION['id'];
$stmt = $mysqlClient->prepare("SELECT NOM_UTILISATEUR, PRENOM FROM utilisateur WHERE ID_UTILISATEUR = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(); 

// Vérifier l'ID et charger l'opération (assurer qu'il s'agit d'un revenu)
if (empty($_GET['id'])) {
    die('ID manquant');
}

$operationId = (int) $_GET['id'];

$stmt = $mysqlClient->prepare("SELECT O.*
  FROM operation O
  JOIN categorie C ON C.ID_CATEGORIE = O.ID_CATEGORIE
  JOIN type T ON T.ID_TYPE = C.ID_TYPE
  WHERE O.ID_OPERATIONS_ = ? AND O.ID_UTILISATEUR = ? AND T.NOM_TYPE = 'Revenu'");
$stmt->execute([$operationId, $userId]);
$operation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$operation) {
    die("Revenu introuvable");
}

// Récupérer les catégories de type Revenu
$catsStmt = $mysqlClient->prepare("SELECT C.* FROM categorie C JOIN type T ON T.ID_TYPE = C.ID_TYPE WHERE T.NOM_TYPE = 'Revenu' ORDER BY C.NOM_CATEGORIE ASC");
$catsStmt->execute();
$categories = $catsStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_categorie = (int)($_POST['categorie'] ?? 0);
    $montant = (float)($_POST['montant'] ?? 0);
    $date_operation = $_POST['date'] ?? '';
    $description = $_POST['note'] ?? '';

    if ($id_categorie && $montant && $date_operation) {
        try {
            // Vérifier que la catégorie choisie est bien un Revenu
            $verif = $mysqlClient->prepare("SELECT 1 FROM categorie C JOIN type T ON T.ID_TYPE = C.ID_TYPE WHERE C.ID_CATEGORIE = ? AND T.NOM_TYPE = 'Revenu'");
            $verif->execute([$id_categorie]);
            if (!$verif->fetch()) {
                $message = "❌ Catégorie invalide pour un revenu.";
            } else {
                $update = $mysqlClient->prepare("UPDATE operation SET ID_CATEGORIE=?, MONTANT=?, DATE_OPERATION=?, DESCRIPTION=? WHERE ID_OPERATIONS_=? AND ID_UTILISATEUR=?");
                $update->execute([$id_categorie, $montant, $date_operation, $description, $operationId, $userId]);
                header('Location: revenus.php');
                exit;
            }
        } catch (PDOException $e) {
            $message = "❌ Erreur lors de la mise à jour : " . $e->getMessage();
        }
    } else {
        $message = "❌ Veuillez remplir tous les champs obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un revenu</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/inscription.css">
    <link rel="stylesheet" href="CSS/revenu.css">
    <link rel="stylesheet" href="CSS/sidebar.css">
     <link rel="stylesheet" href="CSS/head.css">
    <link rel="stylesheet" href="CSS/dropdown.css">
    <script src="JS/dropdown.js" defer></script>
</head>
<body>
    <div class="main-content">
     <header class="head"><h1>Modification de revenu</h1> 
        <div class="profil">
          <a href="profil.php" class="btn-p">
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
    <form id="ajout-revenu" method="post" action="">
          
        <h3>MODIFIER UN REVENU</h3>
        <hr>

        <div>
            <label for="CATEGORIE">Catégorie</label>
            <select id="CATEGORIE" name="categorie" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['ID_CATEGORIE'] ?>" <?= ($operation['ID_CATEGORIE'] == $cat['ID_CATEGORIE']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['NOM_CATEGORIE']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="montant">Montant</label>
            <input type="number" id="montant" name="montant" value="<?= htmlspecialchars($operation['MONTANT']) ?>" placeholder="Ex: 1000" required>
        </div>

        <div>
            <label for="date">Date</label>
            <input type="date" id="date" name="date" max="<?= date('Y-m-d'); ?>" value="<?= htmlspecialchars(date('Y-m-d', strtotime($operation['DATE_OPERATION']))) ?>" required>
        </div>

        <div>
            <label for="note">Description</label>
            <input class="desc" id="note" name="note" value="<?= htmlspecialchars($operation['DESCRIPTION'] ?? '') ?>" placeholder="Ex: salaire">
        </div>

        <div class="conf">
            <input type="submit" value="Enregistrer" class="btn-enregistrer">
            <input type="button" value="Annuler" class="btn-annuler" onclick="window.location.href='revenus.php'">
        </div>

        <?php if ($message): ?>
            <p><?= $message ?></p>
        <?php endif; ?>
        <!-- Sidebar -->
    <aside class="sidebar">
        <div  class="titre">
      <a href="accueil.php"><img src="icone/logo.png" alt="logo" class="logo" style="cursor: pointer;" ></a>
      <h1>SAMA KALPE</h1>
      </div>
      <ul>
        <li><a href="accueil.php" style="text-decoration: none;color:white"><i class="fa-solid fa-house"></i> Accueil</a></li>
        <li><a href="revenus.php" style="text-decoration: none;color:white"><i class="fa-solid fa-wallet"></i> Revenus</a></li>
        <li><a href="depenses.php" style="text-decoration: none;color:white"><i class="fa-solid fa-credit-card"></i> Dépenses</a></li>
        <li><a href="activite.php" style="text-decoration: none;color:white"><i class="fa-solid fa-chart-pie"></i> Activité</a></li>
      </ul>
      <div class="sidebar-footer">
        <a href="deconnexion.php" class="logout-sidebar">
          <i class="fa-solid fa-sign-out-alt"></i> Déconnexion
        </a>
      </div>
    </aside>
    </form>
    </div>
</body>
</html>
