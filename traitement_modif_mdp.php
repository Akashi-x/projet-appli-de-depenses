<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nouveau_mot_de_passe = trim($_POST['NOUVEAU_MOT_DE_PASSE']);
    $confirmation_mot_de_passe = trim($_POST['CONFIRMATION_MOT_DE_PASSE']);
    $email = trim($_POST['EMAIL']);
    
    if (empty($nouveau_mot_de_passe)) {
        $error = "Veuillez saisir un nouveau mot de passe.";
    } elseif (strlen($nouveau_mot_de_passe) < 8) {
        $error = "Le mot de passe doit contenir au moins 8 caractères.";
    } elseif ($nouveau_mot_de_passe !== $confirmation_mot_de_passe) {
        $error = "Les deux mots de passe ne correspondent pas.";
    } elseif (empty($email)) {
        $error = "Email manquant. Veuillez refaire la procédure de récupération.";
    } else {  
        try {
            $mot_de_passe_hash = password_hash($nouveau_mot_de_passe, PASSWORD_ARGON2I);
            
            $checkSql = "SELECT ID_UTILISATEUR FROM UTILISATEUR WHERE EMAIL = ? AND FLAG_REINITIALISATION = 0";
            $checkStmt = $mysqlClient->prepare($checkSql);
            $checkStmt->execute([$email]);
            $user = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                $error = "Utilisateur non trouvé ou procédure de récupération non terminée. Veuillez refaire la procédure.";
            } else {

                $sql = "UPDATE UTILISATEUR SET MOT_DE_PASSE = ? WHERE EMAIL = ?";
                $stmt = $mysqlClient->prepare($sql);
                $stmt->execute([$mot_de_passe_hash, $email]);
                
                if ($stmt->rowCount() > 0) {
                    $success = "Mot de passe modifié avec succès !";
                } else {
                    $error = "Aucune modification effectuée. Vérifiez que l'utilisateur existe.";
                }
            }
            
        } catch (Exception $e) {
            $error = "Erreur de base de données : " . $e->getMessage();
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
    <title>Creation nouveau mot de passe</title>
</head>
<body>
     <P><strong class="ct">Creation d'un nouveau mot de passe</strong></P>
    
    <?php if (isset($error)): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <div class="success-message">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php else: ?>
        <form class="form_conf" method="post" action="traitement_modif_mdp.php">
        
         <div class="mdp_conf">
            <label>Nouveau mot de passe</label>
            <input type="password" name="NOUVEAU_MOT_DE_PASSE" placeholder="Créer un nouveau mot de passe" minlength="8" required> 
        
            <label>Confirmation mot de passe</label>
            <input type="password" name="CONFIRMATION_MOT_DE_PASSE" placeholder="Confirmer le nouveau mot de passe" minlength="8" required> <br>
            
            <input type="hidden" name="EMAIL" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">
            
            <div class="sub_conf">
                <input type="submit" value="Enregistrer" >
            </div>
        </div>

    </form>
    <?php endif; ?>

</body>
</html>
