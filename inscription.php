<?php
session_start();
require_once __DIR__ . '/config/config.php';

// Si déjà connecté, rediriger vers l'accueil
if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    header("Location: accueil.php");
    exit();
}

$message = '';

// Traitement du formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = htmlspecialchars($_POST["PRENOM"]);
    $nom_utilisateur = htmlspecialchars($_POST["NOM_UTILISATEUR"]);
    $email = htmlspecialchars($_POST["EMAIL"]);
    $mot_de_passe = $_POST["MOT_DE_PASSE"];
    $confirmation = $_POST["CONFIRM_MOT_DE_PASSE"] ?? '';

    if (strlen($mot_de_passe) < 8) {
        header("location: inscription.php?code=3");
        exit();
    }

    if ($mot_de_passe !== $confirmation) {
        header("location: inscription.php?code=4");
        exit();
    }

    $hash = password_hash($mot_de_passe, PASSWORD_ARGON2ID);

    $check = $mysqlClient->prepare("SELECT EMAIL FROM utilisateur WHERE EMAIL = ?");
    $check->execute([$email]);
    if ($check->rowCount() > 0) {
        header("location: inscription.php?code=1");
       
    } else {
        $sql = "INSERT INTO UTILISATEUR (PRENOM, NOM_UTILISATEUR, EMAIL, MOT_DE_PASSE,ID_ROLE) VALUES (?, ?, ?, ?, 2)";
        $stmtInsert = $mysqlClient->prepare($sql);
        $stmtInsert->execute([$prenom, $nom_utilisateur, $email, $hash]);
        header("location: inscription.php?code=2");
        exit();
    }
}

if (isset($_GET['code'])) {
    $code = $_GET['code'];
    if ($code == '1') {
        $message = '<p style="color: red; font-size: 18px;">❌ Cet e-mail a déjà été utilisé.</p>';
    } else if ($code == '2') {
        $message = '<p style="color: green; font-size: 18px;">✅ Inscription réussie !</p>';
    } else if ($code == '3') {
        $message = '<p style="color: red; font-size: 18px;">❌ Le mot de passe doit contenir au moins 8 caractères.</p>';
    } else if ($code == '4') {
        $message = '<p style="color: red; font-size: 18px;">❌ Les mots de passe ne correspondent pas.</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/inscription.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/entete.css">
    <title>Page d'inscription</title>
</head>
<body>
    <div class="entete">
    <a href="index.php"><img src="icone/logo.png" alt="logo" class="logo" style="cursor: pointer;"></a>
    <h1 class="site-title">SAMA KALPE</h1>
    <a class="prop" style="background-color: #1B103E; padding: 10px 12px; border-radius: 4px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);" href="a-propos.php">À propos</a>
    <a style="background-color: #1B103E; padding: 10px 12px; border-radius: 4px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);" href="index.php">Connexion</a>
    </div>
    <div>
        <div>
    <form action="inscription.php" method="post" id="inscription">
      
        <h3>INSCRIPTION</h3> <hr>
        
        <div>
        <label>Prénom</label>
        <input type="text" name="PRENOM" placeholder="Entrez votre prénom"required>
        </div>
         <div>
        <label>Nom</label>
        <input type="text" name="NOM_UTILISATEUR" 
        placeholder="Entrez votre Nom" required> <br>
        </div>
        
        <div>
            <label>Adresse e-mail</label>
        <input type="email" name="EMAIL" placeholder="Entrez votre e-mail" required> 
        </div>
         
        <div>
            <label>Mot de passe</label>
        <input type="password" name="MOT_DE_PASSE" placeholder="Entrez votre mot de passe" required minlength="8"> <br>
        </div>
        <div>
            <label>Confirmer le mot de passe</label>
        <input type="password" name="CONFIRM_MOT_DE_PASSE" placeholder="Confirmez votre mot de passe" required minlength="8"> <br> <br>
        </div>
        <div class="conf">
        <input type="submit" value="S'inscrire">
        <input type="reset" value="Annuler">
        </div>
        <p> Vous avez déja un compte?<a style="text-decoration: none; " href="index.php">Connectez-vous</a></p>
       
       
       <?php echo $message; ?>
         
        
    </form>
    </div>  

</body>
</html>