<?php
// apropos.php
require_once __DIR__ . "/config/config.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="CSS/apropos.css">
  <link rel="stylesheet" href="CSS/entete.css">
  <title>À propos</title>
</head>
<body>
   <div class="entete">
    <a href="index.php"><img src="icone/logo.png" alt="logo" class="logo" style="cursor: pointer;"></a>
    <h1 class="site-title">SAMA KALPE</h1>
    
    <a class="prop" style="background-color: #1B103E; padding: 10px 12px; border-radius: 4px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);" href="a-propos.php">À propos</a>
    <a style="background-color: #1B103E; padding: 10px 12px; border-radius: 4px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);" href="index.php">Connexion</a>
    </div>
  <div class="equipee">
  <h1>A propos de notre application</h1>
  </div>
    <div class="container">
        <p>
            L'application <strong>SAMA KALPE</strong> a été créée pour aider les utilisateurs 
            à suivre leurs revenus, dépenses et opérations financières de manière simple, claire et efficace.
        </p>

        <p>
            Grâce à une interface intuitive, vous pouvez enregistrer vos opérations, consulter vos statistiques, 
            et mieux comprendre votre budget personnel ou familial.
        </p>
   </div> 
   <div class="equipe">
   <h2>L'équipe du projet:</h2><br>
   </div>
  <div class="team">
    <div class="member">
      <img src="IMG/BOC.jpg" alt="Membre 1">
      <h3>ABDOUL BOCOUM</h3>
    </div>
    <div class="member">
      <img src="IMG/PA.jpg" alt="Membre 2">
      <h3>PAPA SAMBA MBODJI</h3>
    </div>
    <div class="member">
      <img src="IMG/BENJ.jpg" alt="Membre 3">
      <h3>BENJAMIN IBA NDIAYE</h3>
    </div>
    <div class="member4">
      <img src="IMG/DEV.jpg" alt="Membre 4">
      <h3>DAOUDA SARR</h3>
      <p>CHEF DE PROJET</p>
    </div>
  </div>

</body>
</html> 