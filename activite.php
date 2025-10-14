<?php
require_once 'check_session.php';
require_once 'config/config.php';

$userId = $_SESSION['id'];

$stmt = $mysqlClient->prepare("SELECT NOM_UTILISATEUR, PRENOM FROM utilisateur WHERE ID_UTILISATEUR = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();


$stmt = $mysqlClient->prepare(
    "SELECT o.DESCRIPTION, o.MONTANT, t.NOM_TYPE, c.NOM_CATEGORIE 
     FROM operation o 
     INNER JOIN categorie c ON o.ID_CATEGORIE = c.ID_CATEGORIE 
     INNER JOIN type t ON c.ID_TYPE = t.ID_TYPE 
     WHERE o.ID_UTILISATEUR = ? 
     AND MONTH(o.DATE_OPERATION) = MONTH(CURRENT_DATE()) 
     AND YEAR(o.DATE_OPERATION) = YEAR(CURRENT_DATE())
     ORDER BY o.MONTANT DESC"
);
$stmt->execute([$userId]);
$operations = $stmt->fetchAll();

$total = 0;
foreach ($operations as $operation) { $total += (float)$operation['MONTANT']; }


$colorPalette = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#C7C7C7', '#5366FF', '#FF63FF', '#63FF84'];

$labels = [];
$values = [];
$backgrounds = [];
foreach ($operations as $index => $op) {
  $labels[] = $op['NOM_CATEGORIE'];
  $values[] = (float)$op['MONTANT'];
  $backgrounds[] = $colorPalette[$index % count($colorPalette)];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" href="CSS/accueil.css">
  <link rel="stylesheet" href="CSS/activite.css">
  <link rel="stylesheet" href="CSS/dropdown.css">
  <link rel="stylesheet" href="CSS/sidebar.css">
  <title>Activité - Suivi Dépenses</title>
</head>
<body>
  <div class="container">
     <!-- Sidebar -->
    <aside class="sidebar">
        <div class="titre">
      <a href="accueil.php"><img src="icone/logo.png" alt="logo" class="logo" style="cursor: pointer;"></a>
      <p>Gérez vos finances</p>
      </div>
      <ul>
        <li ><a href="accueil.php" style="text-decoration: none;color:white"><i class="fa-solid fa-house"></i> Accueil</a></li>
        <li><a href="revenus.php" style="text-decoration: none;color:white"><i class="fa-solid fa-wallet"></i> Revenus</a></li>
        <li><a href="depenses.php" style="text-decoration: none;color:white"><i class="fa-solid fa-credit-card"></i> Dépenses</a></li>
        <li class="active"><a href="activite.php" style="text-decoration: none;color:white"><i class="fa-solid fa-chart-pie"></i> Activité</a></li>
      </ul>
      <div class="sidebar-footer">
        <a href="deconnexion.php" class="logout-sidebar">
          <i class="fa-solid fa-sign-out-alt"></i> Déconnexion
        </a>
      </div>
    </aside>


    <main class="main activite">
      <header class="header">
        <h1>Activité</h1> 
        <div class="user-profile">
          <a href="profil.php" class="profile-btn">
            <i class="fa-solid fa-user"></i>
          </a>
          <div class="user-dropdown">
            <span class="user-name" onclick="toggleDropdown()"><?php echo $user['NOM_UTILISATEUR']; ?></span>
            <div class="dropdown-menu" id="userDropdown">
              <a href="edit_profil.php"><i class="fa-solid fa-user-edit"></i> Modifier Profil</a>
            </div>
          </div>
        </div>
      </header>

      <section class="introduction">
        <h1>📈 Revenus et dépenses au cours de l'année</h1>      
        <p>Visualisez vos opérations financières</p>
      </section>

      <section class="graph-container">
        <h2>Diagramme circulaire des opérations</h2>
        <div class="pie-chart">
          <canvas id="operationsChart" width="400" height="400"></canvas>
        </div>

        <div class="legend activite">
          <h3 style="color: #742CB4; margin-bottom: 20px;">Légende des opérations</h3>
          <div class="legend-items">
          <?php
            foreach ($operations as $index => $operation) {
                $pourcentage = $total > 0 ? round(((float)$operation['MONTANT'] / $total) * 100, 2) : 0;
                $couleur = $colorPalette[$index % count($colorPalette)];
                $typeColor = $operation['NOM_TYPE'] == 'Revenu' ? '#55ff55' : '#ff5555';
                $signe = $operation['NOM_TYPE'] == 'Revenu' ? '+' : '-';
                echo '<div class="legend-item">';
                echo '<div class="legend-color" style="background-color: ' . $couleur . ';"></div>';
                echo '<div class="legend-text">';
                echo '<div class="legend-name">' . htmlspecialchars($operation['NOM_CATEGORIE']) . '</div>';
                echo '<div class="legend-details">';
                echo '<span style="color: ' . $typeColor . ';">' . $signe . number_format($operation['MONTANT']) . ' FCFA</span> ';
                echo '<span style="color: #742CB4;">(' . $pourcentage . '%)</span>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
          ?>
          </div>
        </div>
      </section>

      <section class="stats activite">
        <h2>Statistiques générales</h2>
        <?php
        
        $revenusTotal = 0; $depensesTotal = 0;
        foreach ($operations as $op) {
            if ($op['NOM_TYPE'] === 'Revenu') { $revenusTotal += (float)$op['MONTANT']; }
            else { $depensesTotal += (float)$op['MONTANT']; }
        }
        ?>
        <div class="stats-grid">
          <div class="stat-item">
            <h4>Total des opérations</h4>
            <div class="stat-value"><?php echo number_format($total); ?> FCFA</div>
          </div>
          <div class="stat-item">
            <h4>Nombre d'opérations</h4>
            <div class="stat-value"><?php echo count($operations); ?></div>
          </div>
          <div class="stat-item">
            <h4>Total revenus</h4>
            <div class="stat-value" style="color: #55ff55;">+ <?php echo number_format($revenusTotal); ?> FCFA</div>
          </div>
          <div class="stat-item">
            <h4>Total dépenses</h4>
            <div class="stat-value" style="color: #ff5555;">- <?php echo number_format($depensesTotal); ?> FCFA</div>
          </div>
        </div>
      </section>
    </main>
  </div>

  <script src="JS/chart.umd.min.js"></script>
  <script>
    const labels = <?php echo json_encode($labels, JSON_UNESCAPED_UNICODE); ?>;
    const dataValues = <?php echo json_encode($values, JSON_UNESCAPED_UNICODE); ?>;
    const backgroundColors = <?php echo json_encode($backgrounds, JSON_UNESCAPED_UNICODE); ?>;
    const total = <?php echo (int)$total; ?>;

    // Plugin pour texte central
    const centerText = {
      id: 'centerText',
      afterDraw(chart, args, options) {
        const {ctx, chartArea: {width, height}} = chart;
        ctx.save();
        ctx.fillStyle = '#742CB4';
        ctx.font = 'bold 16px Poppins, sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('Total', width / 2, height / 2 - 10);
        ctx.fillStyle = '#e5e7eb';
        ctx.font = '14px Poppins, sans-serif';
        ctx.fillText(new Intl.NumberFormat('fr-FR').format(total) + ' FCFA', width / 2, height / 2 + 12);
        ctx.restore();
      }
    };

    const ctx = document.getElementById('operationsChart').getContext('2d');
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels,
        datasets: [{
          data: dataValues,
          backgroundColor: backgroundColors,
          borderColor: '#1b103e',
          borderWidth: 2,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '45%',
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: (ctx) => {
                const val = ctx.parsed;
                const pct = total > 0 ? (val / total * 100) : 0;
                return `${ctx.label}: ${new Intl.NumberFormat('fr-FR').format(val)} FCFA (${pct.toFixed(2)}%)`;
              }
            }
          }
        }
      },
      plugins: [centerText]
    });

    // Fonction pour le menu déroulant
    function toggleDropdown() {
      const dropdown = document.getElementById('userDropdown');
      dropdown.classList.toggle('show');
    }

    // Fermer le menu si on clique ailleurs
    window.onclick = function(event) {
      if (!event.target.matches('.user-info') && !event.target.closest('.user-dropdown')) {
        const dropdown = document.getElementById('userDropdown');
        if (dropdown.classList.contains('show')) {
          dropdown.classList.remove('show');
        }
      }
    }
  </script>

  <script src="JS/dropdown.js"></script>
</body>
</html>
