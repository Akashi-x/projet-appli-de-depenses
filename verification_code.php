<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code_saisi = trim($_POST['CODE'] );
    
    if (empty($code_saisi)) {
        $error = "Veuillez saisir le code reçu par email.";
    } elseif (!preg_match('/^[0-9]{6}$/', $code_saisi)) {
        $error = "Le code doit contenir exactement 6 chiffres.";
            } else {
            
            $email = $_POST['EMAIL'] ?? '';
            if (empty($email)) {
                $error = "Email manquant pour la vérification du code.";
            } else {
               
                $stmt = $mysqlClient->prepare("SELECT ID_UTILISATEUR, CODE, FLAG_REINITIALISATION FROM UTILISATEUR WHERE EMAIL = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$user || $user['FLAG_REINITIALISATION'] != 1 || $user['CODE'] == 0) {
                    $error = "Code expiré ou invalide. Veuillez demander un nouveau code.";
                } elseif ($code_saisi != $user['CODE']) {
                    $error = "Code incorrect. Veuillez vérifier le code reçu par email.";
                } else {
    
                    $updateStmt = $mysqlClient->prepare("UPDATE UTILISATEUR SET FLAG_REINITIALISATION = 0, CODE = 0 WHERE EMAIL = ?");
                    $updateStmt->execute([$email]);
                    header("Location: traitement_modif_mdp.php?email=" . urlencode($email));
                    exit;
                }
            }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style_inscription.css">
    <title>Saisir le code de réinitialisation</title>
</head>
<body>
    <P><strong class="st">Saisir le code de réinitialisation</strong></P>
    
    <?php if (isset($_GET['email'])): ?>
        <div class="success-message">
            Code envoyé avec succès ! Vérifiez votre email.
        </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    
    <form class="form_mdp" method="post" action="verification_code.php">
        
         <div  class="inp_mdp">
            <label>Code reçu par email</label>
            <input type="text" name="CODE" placeholder="Entrez le code à 6 chiffres" required maxlength="6" pattern="[0-9]{6}" value="<?php echo htmlspecialchars($_POST['CODE'] ?? ''); ?>"> 
        </div>
        
        <input type="hidden" name="EMAIL" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">
        
          <div class="sub_mdp">
            <input type="submit" value="Vérifier le code">
        </div>

    </form>

</body>
</html>
