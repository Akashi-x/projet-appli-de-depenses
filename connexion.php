<?php
    require_once __DIR__ . '/config/config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['EMAIL'], $_POST['MOT_DE_PASSE'])) {
    $email = htmlspecialchars($_POST["EMAIL"]);  
    $pass = htmlspecialchars($_POST['MOT_DE_PASSE']);

    if ($email !== "" && $pass !== "") {
        $stmt = $mysqlClient->prepare("SELECT * FROM UTILISATEUR WHERE EMAIL = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($pass, $user['MOT_DE_PASSE'])) {
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
    <img  src="icone/logo.png" alt="logo" class="logo">
    <a class="prop" style="background-color: #1B103E; padding: 10px 12px; border-radius: 4px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);" href="a-propos.php">À propos</a>
    <a style="background-color: #1B103E; padding: 10px 12px; border-radius: 4px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);" href="connexion.php">Connexion</a>
    </div>
    <div>
        <div>
    <form action="connexion.php" method="post" id="connexion">
      
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
        <input type="submit" value="S'inscrire">
        <input type="reset" value="Annuler">
        </div>
        <p>Vous n'avez pas de compte?  <a href="inscription.php">Créer un compte</a></p>
       
       
       <?php echo $message; ?>
         
        
    </form>
    </div>  

</body>
</html>
