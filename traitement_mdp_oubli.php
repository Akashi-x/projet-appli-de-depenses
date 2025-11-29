<?php
require_once 'sendmail.php';
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['EMAIL']);

    if (empty($email)) {
        $error = "Veuillez saisir une adresse email valide.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Veuillez saisir une adresse email valide.";
    } else {
        $stmt = $mysqlClient->prepare("SELECT ID_UTILISATEUR, CODE, FLAG_REINITIALISATION FROM utilisateur WHERE EMAIL = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            $error = "Aucun compte associé à cette adresse email.";
        } else {
           
            $code = sprintf("%06d", mt_rand(0, 999999));
            
            $updateStmt = $mysqlClient->prepare("UPDATE utilisateur SET CODE = ?, FLAG_REINITIALISATION = 1 WHERE EMAIL = ?");
            $updateResult = $updateStmt->execute([$code, $email]);
            
            if (!$updateResult) {
                $error = "Erreur lors de la sauvegarde du code de récupération. Veuillez réessayer.";
            } else {
                  
        $subject = "Code de réinitialisation - Application Suivi Dépenses";
        
        $htmlBody = "
        <div class='email-container'>
            <div class='email-header'>
                <h1>🔐 Application Suivi Dépenses</h1>
                <h2>Réinitialisation de mot de passe</h2>
            </div>
            
            <div class='email-content'>
                <p>Bonjour,</p>
                
                <p>Vous avez demandé la réinitialisation de votre mot de passe pour l'<strong>Application Suivi Dépenses</strong>.</p>
                
                <p>Voici votre code de réinitialisation :</p>
                
                <!-- Code de réinitialisation -->
                <div class='email-code' style='font-size: 48px;'>
                    $code
                </div>
            
                <div class='email-warning'>
                    <p>⚠️ Important :</p>
                    <ul>
                        <li>Ce code est valide pendant 15 minutes</li>
                        <li>Ne partagez ce code avec personne</li>
                        <li>Si vous n'avez pas demandé cette réinitialisation, ignorez cet email</li>
                    </ul>
                </div>
                
                <p>Utilisez ce code sur la page de réinitialisation pour créer un nouveau mot de passe.</p>
            </div>
            
            <div class='email-footer'>
                <p>
                    Cordialement,<br>
                    Équipe Application Suivi Dépenses
                </p>
                <p class='email-note'>
                    Cet email a été envoyé automatiquement, merci de ne pas y répondre.
                </p>
            </div>
        </div>";
        
        $textBody = "
        Code de réinitialisation - Application Suivi Dépenses
        
        Bonjour,
        
        Vous avez demandé la réinitialisation de votre mot de passe pour l'Application Suivi Dépenses.
        
        Votre code de réinitialisation est : $code
        
        IMPORTANT :
        - Ce code est valide pendant 15 minutes
        - Ne partagez ce code avec personne
        - Si vous n'avez pas demandé cette réinitialisation, ignorez cet email
        
        Utilisez ce code sur la page de réinitialisation pour créer un nouveau mot de passe.
        
        Cordialement,
        Équipe Application Suivi Dépenses
        
        Cet email a été envoyé automatiquement, merci de ne pas y répondre.";
        
        // Envoyer l'email
        $result = sendEmail($email, $subject, $htmlBody, $textBody);
        
        if ($result['success']) {
        
            header("Location: verification_code.php?email=" . urlencode($email));
            exit;
        } else {
            $error = "Erreur lors de l'envoi de l'email : " . $result['message'];
        }
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
    <link rel="stylesheet" href="CSS/inscription.css"> 
    <link rel="stylesheet" href="CSS/entete.css">                               
    <title>Réinitialiser votre mot de passe</title>
</head>
<body>
     <div class="entete">
    <a href="index.php"><img src="icone/logo.png" alt="logo" class="logo" style="cursor: pointer;"></a> 
    <h1 class="site-title">SAMA KALPE</h1>

  
    <a class="prop" style="background-color: #1B103E; padding: 10px 12px; border-radius: 4px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);" href="a-propos.php">À propos</a>
    <a style="background-color: #1B103E; padding: 10px 12px; border-radius: 4px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);" href="index.php">Connexion</a>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <div class="success-message">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>
   
    
    <form style="margin-top:190px;"class="form_mdp" method="post" action="traitement_mdp_oubli.php">
         <P><strong class="st">Réinitialiser votre mot de passe</strong></P>
         <div  class="inp_mdp">
            <label>Adresse e-mail</label>
            <input type="email" name="EMAIL" placeholder="Entrez votre e-mail" required value="<?php echo htmlspecialchars($_POST['EMAIL'] ?? ''); ?>"> 
        </div>
          <div class="sub_mdp">
            <input type="submit" value="Envoyer le code">
        </div>

    </form>
</div>
</body>
</html>
