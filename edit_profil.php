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
<link rel="stylesheet" href="CSS/editprofil.css">

</head>
<body>
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
</form>
</body>
</html>
