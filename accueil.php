<?php
require_once 'config/config.php';

$userId = 5;


$stmt = $mysqlClient->prepare("SELECT NOM_UTILISATEUR, PRENOM FROM utilisateur WHERE ID_UTILISATEUR = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(); 

// Revenus totaux
$stmt = $mysqlClient->prepare("SELECT SUM(o.MONTANT) as total FROM operation o, categorie c, type t WHERE o.ID_CATEGORIE = c.ID_CATEGORIE AND c.ID_TYPE = t.ID_TYPE AND t.NOM_TYPE = 'Revenu' AND o.ID_UTILISATEUR = ? AND MONTH(o.DATE_OPERATION) = MONTH(CURRENT_DATE()) AND YEAR(o.DATE_OPERATION) = YEAR(CURRENT_DATE())");
$stmt->execute([$userId]);
$revenus = $stmt->fetch();

// Dépenses totales
$stmt = $mysqlClient->prepare("SELECT SUM(o.MONTANT) as total FROM operation o, categorie c, type t WHERE o.ID_CATEGORIE = c.ID_CATEGORIE AND c.ID_TYPE = t.ID_TYPE AND t.NOM_TYPE = 'Depense' AND o.ID_UTILISATEUR = ? AND MONTH(o.DATE_OPERATION) = MONTH(CURRENT_DATE()) AND YEAR(o.DATE_OPERATION) = YEAR(CURRENT_DATE())");
$stmt->execute([$userId]);
$depenses = $stmt->fetch();

// 5 dernières transactions
$stmt = $mysqlClient->prepare("SELECT c.NOM_CATEGORIE, t.NOM_TYPE, o.DESCRIPTION, o.DATE_OPERATION, o.MONTANT FROM operation o, categorie c, type t WHERE o.ID_CATEGORIE = c.ID_CATEGORIE AND c.ID_TYPE = t.ID_TYPE AND o.ID_UTILISATEUR = ? ORDER BY o.DATE_OPERATION DESC LIMIT 5");
$stmt->execute([$userId]);
$transactions = $stmt->fetchAll();

// Solde actuel (revenus - dépenses)
$totalRevenus = (float)($revenus['total'] );
$totalDepenses = (float)($depenses['total'] );
$soldeActuel = $totalRevenus - $totalDepenses;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" href="CSS/accueil.css">
  <title>Suivi Dépenses</title>
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="titre">
      <h2>💰 Suivi Dépenses</h2>
      <p>Gérez vos finances</p>
      </div>
      <ul>
        <li class="active"><i class="fa-solid fa-house"></i> Accueil</li>
        <li><i class="fa-solid fa-wallet"></i> Revenus</li>
        <li><a href="mesdepenses.php" style="text-decoration: none;color:white"><i class="fa-solid fa-credit-card"></i> Dépenses</a></li>
        <li><a href="activite.php" style="text-decoration: none;color:white"><i class="fa-solid fa-chart-pie"></i> Activité</a></li>
      </ul>
    </aside>

    <!-- Main -->
    <main class="main">
      <header class="header"><h1>Accueil</h1> 
      <div class="user-profile">
          <a href="profil.php" class="profile-btn">  <i class="fa-solid fa-user"></i></a>
        <div class="D">
        <h4><?php echo $user['PRENOM']; ?></h4>
        <p>Utilisateur</p>
        </div>
        </div>
    </header>

      <section class="introduction">
         <h1> 👋 Bonjour, <?php echo $user['PRENOM']; ?> !</h1>      
        <p>Bienvenue sur votre tableau de bord de suivi des dépenses</p>
      </section>

      <section class="solde">
        Solde actuel de votre compte<br>
        <span class="solde-valeur <?php echo $soldeActuel >= 0 ? 'positif' : 'negatif'; ?>">
          <?php echo $soldeActuel >= 0 ? '+' : ''; ?><?php echo number_format($soldeActuel, 0, ',', ' '); ?> FCFA
        </span>
      </section>

      <section class="stats1">Total revenus du mois<br><span><?php echo ($revenus['total'] ?? 0) > 0 ? '+' : ''; ?><?php echo number_format(($revenus['total'] ?? 0), 0, ',', ' '); ?> FCFA</span></section>
      <section class="stats2">Total dépenses du mois<br><span><?php echo ($depenses['total'] ?? 0) > 0 ? '-' : ''; ?><?php echo number_format(($depenses['total'] ?? 0), 0, ',', ' '); ?> FCFA</span></section>

      <section class="transactions">
        <h2>Dernières transactions</h2>
        <table class="transaction">
          <thead>
            <tr>
              <th>Catégorie</th>
              <th>Type</th>
              <th>Description</th>
              <th>Date</th>
              <th>Montant</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($transactions as $transaction) { 
            ?>
            <tr>
              <td><?php echo $transaction['NOM_CATEGORIE']; ?></td>
              <td><?php echo $transaction['NOM_TYPE']; ?></td>
              <td><?php echo htmlspecialchars($transaction['DESCRIPTION'] ?? ''); ?></td>
              <td><?php echo $transaction['DATE_OPERATION']; ?></td>
              <td class="montant <?php echo $transaction['NOM_TYPE'] == 'Revenu' ? 'positif' : 'negatif'; ?>">
                <?php if ($transaction['NOM_TYPE'] == 'Revenu') echo '+'; else echo '-'; ?>
                <?php echo number_format($transaction['MONTANT'], 0, ',', ' '); ?> FCFA
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </section>
    </main>
  </div>
</body>
</html>
