<?php
require_once __DIR__ . '/config/config.php';


$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'], $_POST['password'])) {
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    if ($email !== "" && $pass !== "") {
        $stmt = $pdo->prepare("SELECT * FROM UTILISATEUR WHERE EMAIL = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($pass, $user['MOT_DE_PASSE'])) {
           
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "Email ou mot de passe incorrect.";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>page de connexion</title>
</head>
<body>
    <form action="connexion.php" method="post" name="formulaires"class="formulaire">
    
  <div class="login"> 
    
    
 <h2>Connexion</h2>

 <?php if (!empty($message)) : ?>
    <div style="
        background-color:rgb(0, 0, 0);
        color:rgb(255, 255, 255);
        font-weight: bold;
        border: 1px solidrgb(255, 255, 255);
        border-radius: 8px;
        padding: 12px;
        margin: 10px auto;
        max-width: 350px;
        text-align: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    ">
        ⚠️ <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>
 

   <label for="email" name="email">Adresse e-mail </label><br>
    <input type="email" id="email" name="email" placeholder="Entrez votre email" required> <br>
    
    <label for="password">Mot de passe</label><br>
   <input type="password" name="password" id="password" placeholder="Entrez votre mot de passe"required> <br>
    
  <div class="but">
     <button class="conect" type="submit" >Se connecter</button>
    <button class="con" type="reset">Annuler</button> <br>
</div>
    
    
    <div class="pas">
        Pas encore de compte ? <a href="#">Créer un compte
        </a>
    </div>

    </form>
    
    
</body>
</html>
