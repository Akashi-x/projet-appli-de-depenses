<?php
require_once(__DIR__ . '/config/config.php');

// Utiliser un ID utilisateur fixe pour les tests
$userId = 5;

// Récupérer les informations de l'utilisateur
$stmt = $mysqlClient->prepare("SELECT NOM_UTILISATEUR, PRENOM, EMAIL FROM utilisateur WHERE ID_UTILISATEUR = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil utilisateur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/inscription.css">
    <link rel="stylesheet" href="CSS/profil.css">
    <link rel="stylesheet" href="CSS/sidebar.css">
</head>
<body>
<!-- Sidebar -->
    <aside class="sidebar">
        <div  class="titre">
      <a href="accueil.php"><img src="icone/logo.png" alt="logo" class="logo" style="cursor: pointer;" ></a>
      <p style="font-size: large;">Gérez vos finances</p>
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
    <div class="Profil">
        <h2>Mon PROFIL</h2>
        <div class="profil-image">
    <img id="preview" src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Photo de profil" class="profile-icon" onclick="document.getElementById('photo').click();"><br><br>
    </div>
    <p><strong>Nom :</strong> <?= htmlspecialchars($user['NOM_UTILISATEUR']) ?></p>
    <p><strong>Prénom :</strong> <?= htmlspecialchars($user['PRENOM']) ?></p>
    <p><strong>Email :</strong> <?= htmlspecialchars($user['EMAIL']) ?></p>
    <a href="edit_profil.php">
        <button type="submit">Modifier</button>
    </a>
    <br>
</div>
    
</body>
</html>
