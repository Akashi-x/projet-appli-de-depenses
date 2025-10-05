<?php
require_once(__DIR__ . '/config/config.php');

// Utiliser l'ID utilisateur fixe comme dans vos autres pages
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
    <link rel="stylesheet" href="CSS/profil.css">
</head>
<body>
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
