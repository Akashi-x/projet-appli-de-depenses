<?php
session_start();
require_once __DIR__ . '/config/config.php';

// Si déjà connecté, rediriger vers l'accueil
if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    header("Location: accueil.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['EMAIL'], $_POST['MOT_DE_PASSE'])) {
    $email = htmlspecialchars($_POST["EMAIL"]);  
    $pass = htmlspecialchars($_POST['MOT_DE_PASSE']);

    if ($email !== "" && $pass !== "") {
        $stmt = $mysqlClient->prepare("SELECT * FROM UTILISATEUR WHERE EMAIL = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($pass, $user['MOT_DE_PASSE'])) {
            // Sécuriser la session après authentification
            session_regenerate_id(true);
            // Créer les sessions
            $_SESSION['id'] = $user['ID_UTILISATEUR'];
            $_SESSION['nom'] = $user['PRENOM'];
            $_SESSION['email'] = $user['EMAIL'];
            
            header("Location: accueil.php");
            exit;
        } else {
            $message = "<p style=\"color: red; font-size: 18px;\">Email ou mot de passe incorrect.</p>";
        }
    } else {
        $message = "<p style=\"color: red; font-size: 18px;\">Veuillez remplir tous les champs.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/entete.css">
    <title>page de connexion</title>
</head>
<body>
    <div class="entete">
    <a href="index.php"><img src="icone/logo.png" alt="logo" class="logo" style="cursor: pointer;"></a>
    <a class="prop" style="background-color: #1B103E; padding: 10px 12px; border-radius: 4px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);" href="a-propos.php">À propos</a>
    <a style="background-color: #1B103E; padding: 10px 12px; border-radius: 4px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);" href="index.php">Connexion</a>
    </div>
    <div>
        <div>
    <form action="index.php" method="post" id="connexion">
      
        <h3>CONNEXION</h3> <hr>
      
         <div>
            <label>Adresse e-mail</label>
        <input type="email" name="EMAIL" placeholder="Entrez votre e-mail" required> 
        </div>
         
        <div>
            <label>Mot de passe</label>
        <input type="password" name="MOT_DE_PASSE" placeholder="Entrez votre mot de passe" required> <br> <br>
        </div>
        <div class="conf">
        <input type="submit" value="Se connecter">
        <input type="reset" value="Annuler">
        </div>
        <div class="ins">
        <p>Vous n'avez pas de compte?  <a style=" text-decoration: none;" href="inscription.php">Créer un compte</a> <br>ou <br>
        <a style=" text-decoration: none;" href="traitement_mdp_oubli.php">Réinitialiser votre mot de passe</a></p>
</div>

       <?php echo $message; ?>
            
        
    </form>
    </div>  

</body>
</html>
