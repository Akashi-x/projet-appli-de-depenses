<?php
require_once(__DIR__ . '/check_session.php');
require_once(__DIR__ . '/config/config.php');

// Utiliser l'ID en session
$userId = $_SESSION['id'];

$stmt = $mysqlClient->prepare("SELECT NOM_UTILISATEUR, PRENOM, EMAIL FROM utilisateur WHERE ID_UTILISATEUR = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['NOM_UTILISATEUR']);
    $prenom = trim($_POST['PRENOM']);

    // Vérification des mots de passe
    if (!empty($_POST['password'])) {
        if ($_POST['password'] !== $_POST['password_confirm']) {
            die("⚠️ Les mots de passe ne correspondent pas !");
        }
        $hashed = password_hash($_POST['password'], PASSWORD_ARGON2ID);
        $stmt = $mysqlClient->prepare("UPDATE utilisateur SET MOT_DE_PASSE = ? WHERE ID_UTILISATEUR = ?");
        $stmt->execute([$hashed, $userId]);
    }

    // Mise à jour infos de base (email non modifiable)
    $stmt = $mysqlClient->prepare("UPDATE utilisateur SET NOM_UTILISATEUR = ?, PRENOM = ? WHERE ID_UTILISATEUR = ?");
    $stmt->execute([$nom, $prenom, $userId]);

    header("Location: profil.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modifier Profil</title>  
 <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<link rel="stylesheet" href="CSS/editprofil.css">
 <link rel="stylesheet" href="CSS/sidebar.css">
     <link rel="stylesheet" href="CSS/head.css">
    <link rel="stylesheet" href="CSS/dropdown.css">
        <script src="JS/dropdown.js" defer></script>



</head>
<body>
    <div class="main-content">
     <header class="head"><h1>Modification de profil</h1> 
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
<form method="post" id="modifier-profil">
    <h3>MODIFIER MON PROFIL</h3>
    <hr>

    <div>
        <label>Prénom</label>
        <input type="text" name="PRENOM" value="<?= htmlspecialchars($user['PRENOM']) ?>" required>
    </div>

    <div>
        <label>Nom</label>
        <input type="text" name="NOM_UTILISATEUR" value="<?= htmlspecialchars($user['NOM_UTILISATEUR']) ?>" required>
    </div>

    <div>
        <label>Email</label>
        <input type="email" value="<?= htmlspecialchars($user['EMAIL']) ?>" disabled>
    </div>

    <div>
        <label>Nouveau mot de passe</label>
        <input type="password" name="password">
    </div>

    <div>
        <label>Confirmer mot de passe</label>
        <input type="password" name="password_confirm">
    </div>

    <div class="conf">
        <input type="submit" value="Enregistrer">
        <input type="reset" value="Annuler" onclick="window.location.href='profil.php'">
    </div>
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
