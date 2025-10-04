<?php
require_once(__DIR__ . '/config/config.php');

// Utiliser l'ID utilisateur fixe comme dans vos autres pages
$userId = 5;

$stmt = $mysqlClient->prepare("SELECT NOM_UTILISATEUR, PRENOM, EMAIL FROM utilisateur WHERE ID_UTILISATEUR = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['NOM_UTILISATEUR']);
    $prenom = trim($_POST['PRENOM']);
    $newEmail = trim($_POST['EMAIL']);

    // Vérification des mots de passe
    if (!empty($_POST['password'])) {
        if ($_POST['password'] !== $_POST['password_confirm']) {
            die("⚠️ Les mots de passe ne correspondent pas !");
        }
        $hashed = password_hash($_POST['password'], PASSWORD_ARGON2ID);
        $stmt = $mysqlClient->prepare("UPDATE utilisateur SET MOT_DE_PASSE = ? WHERE ID_UTILISATEUR = ?");
        $stmt->execute([$hashed, $userId]);
    }

    // Mise à jour infos de base
    $stmt = $mysqlClient->prepare("UPDATE utilisateur SET NOM_UTILISATEUR = ?, PRENOM = ?, EMAIL = ? WHERE ID_UTILISATEUR = ?");
    $stmt->execute([$nom, $prenom, $newEmail, $userId]);

    header("Location: profil.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Modifier Profil</title>
<link rel="stylesheet" href="CSS\editprofil.css">
</head>
<body>
<div class="Profil-container">
    <h2>Modifier mon PROFIL</h2>
    <form method="post">
        <label>Prénom</label>
        <input type="text" class="champ" name="PRENOM" value="<?= htmlspecialchars($user['PRENOM']) ?>" required>

        <label>Nom</label>
        <input type="text" class="champ" name="NOM_UTILISATEUR" value="<?= htmlspecialchars($user['NOM_UTILISATEUR']) ?>" required>

        <label>Email</label>
        <input type="email" class="champ" name="EMAIL" value="<?= htmlspecialchars($user['EMAIL']) ?>" required>

        <label>Nouveau mot de passe</label>
        <input type="password" class="champ" name="password">

        <label>Confirmer mot de passe</label>
        <input type="password" class="champ" name="password_confirm">

        <div class="but">
            <button type="submit">Enregistrer</button>
            <button type="button" onclick="window.location.href='mesdepenses.php'">Annuler</button>
        </div>
    </form>
</div>
</body>
</html>
