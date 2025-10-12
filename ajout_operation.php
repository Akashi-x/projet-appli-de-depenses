<?php
require_once __DIR__ . '/config/config.php';

$message = "";
$userId = 5; // À remplacer par la session
$stmt = $mysqlClient->prepare("SELECT NOM_UTILISATEUR, PRENOM FROM utilisateur WHERE ID_UTILISATEUR = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(); 


// Récupérer toutes les catégories (revenus et dépenses)
$catsStmt = $mysqlClient->prepare("SELECT C.*, T.NOM_TYPE FROM categorie C JOIN type T ON T.ID_TYPE = C.ID_TYPE ORDER BY T.NOM_TYPE, C.NOM_CATEGORIE ASC");
$catsStmt->execute();
$allCategories = $catsStmt->fetchAll(PDO::FETCH_ASSOC);

// Séparer les catégories par type
$revenuCategories = [];
$depenseCategories = [];

foreach ($allCategories as $cat) {
    if ($cat['NOM_TYPE'] === 'Revenu') {
        $revenuCategories[] = $cat;
    } else {
        $depenseCategories[] = $cat;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_utilisateur = $userId;
    $id_categorie = (int)($_POST['categorie'] ?? 0);
    $montant = (float)($_POST['montant'] ?? 0);
    $date_operation = $_POST['date'] ?? '';
    $description = $_POST['note'] ?? '';
    $type_operation = $_POST['type_operation'] ?? '';

    if ($id_utilisateur && $id_categorie && $montant && $date_operation && $type_operation) {
        try {
            // Vérifier que la catégorie correspond au type d'opération
            $verif = $mysqlClient->prepare("SELECT 1 FROM categorie C JOIN type T ON T.ID_TYPE = C.ID_TYPE WHERE C.ID_CATEGORIE = ? AND T.NOM_TYPE = ?");
            $verif->execute([$id_categorie, $type_operation]);
            if (!$verif->fetch()) {
                $message = "❌ Catégorie invalide pour ce type d'opération.";
            } else {
                $stmt = $mysqlClient->prepare("INSERT INTO OPERATION (ID_UTILISATEUR, ID_CATEGORIE, MONTANT, DATE_OPERATION, DESCRIPTION) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$id_utilisateur, $id_categorie, $montant, $date_operation, $description]);
                
                // Redirection selon le type d'opération
                if ($type_operation === 'Revenu') {
                    header('Location: revenus.php');
                } else {
                    header('Location: depenses.php');
                }
                exit;
            }
        } catch (PDOException $e) {
            $message = "❌ Erreur lors de l'ajout : " . $e->getMessage();
        }
    } else {
        $message = "❌ Veuillez remplir tous les champs obligatoires."; 
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une opération</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/inscription.css">
    <link rel="stylesheet" href="CSS/operation.css">
    <link rel="stylesheet" href="CSS/sidebar.css">
    <link rel="stylesheet" href="CSS/head.css">
    <link rel="stylesheet" href="CSS/dropdown.css">
    <script src="JS/dropdown.js" defer></script>
</head>
<body>
    <div class="main-content">
     <header class="head" style="margin-top: 80px;"><h1>Ajout d'operation</h1> 
        <div class="profil">
          <a href="profil.php" class="btn-p">
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
    <form id="ajout-operation" method="post" action="">
      
        
        <h3>AJOUTER UNE OPÉRATION</h3>
        <hr>

        <div>
            <label for="type_operation">Type d'opération</label>
            <select id="type_operation" name="type_operation" required>
                <option value="">-- Choisir le type --</option>
                <option value="Revenu">💰 Revenu</option>
                <option value="Depense">💸 Dépense</option>
            </select>
        </div>

        <div>
            <label for="categorie">Catégorie</label>
            <select id="categorie" name="categorie" required>
                <option value="">-- Choisir le type d'abord --</option>
            </select>
        </div>

        <div>
            <label for="montant">Montant</label>
            <input type="number" id="montant" name="montant" step="0.01" placeholder="Ex: 1000" required>
        </div>

        <div>
            <label for="date">Date</label>
            <input type="date" id="date" name="date" max="<?php echo date('Y-m-d'); ?>" required>
        </div>

        <div>
            <label for="note">Description</label>
            <input class="desc" id="note" name="note" placeholder="Ex: salaire, courses, etc.">
        </div>

        <div class="conf">
            <input type="submit" value="Ajouter">
            <input type="button" value="Retour" onclick="window.location.href='accueil.php'">
        </div>
        
        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        </div>
        <!-- Sidebar -->
    <aside class="sidebar">
        <div  class="titre">
      <a href="accueil.php"><img src="icone/logo.png" alt="logo" class="logo" style="cursor: pointer;" ></a>
      <p style="font-size: large;">Gérez vos finances</p>
      </div>
      <ul>
        <li><a href="accueil.php" style="text-decoration: none;color:white"><i class="fa-solid fa-house"></i> Accueil</a></li>
        <li><a href="revenus.php" style="text-decoration: none;color:white"><i class="fa-solid fa-wallet"></i> Revenus</a></li>
        <li><a href="depenses.php" style="text-decoration: none;color:white"><i class="fa-solid fa-credit-card"></i> Dépenses</a></li>
        <li><a href="activite.php" style="text-decoration: none;color:white"><i class="fa-solid fa-chart-pie"></i> Activité</a></li>
      </ul>
      <div class="sidebar-footer">
        <a href="deconnexion.php" class="logout-sidebar">
          <i class="fa-solid fa-sign-out-alt"></i> Déconnexion
        </a>
      </div>
    </aside>
    </form>

    <script>
        // Données des catégories côté client
        const categoriesData = {
            'Revenu': <?php echo json_encode($revenuCategories); ?>,
            'Depense': <?php echo json_encode($depenseCategories); ?>
        };

        document.getElementById('type_operation').addEventListener('change', function() {
            const typeOperation = this.value;
            const categorieSelect = document.getElementById('categorie');
            
            // Vider les options existantes
            categorieSelect.innerHTML = '<option value="">-- Choisir le type d\'abord --</option>';
            
            if (typeOperation && categoriesData[typeOperation]) {
                // Ajouter les options pour le type sélectionné
                categoriesData[typeOperation].forEach(function(categorie) {
                    const option = document.createElement('option');
                    option.value = categorie.ID_CATEGORIE;
                    option.textContent = categorie.NOM_CATEGORIE;
                    categorieSelect.appendChild(option);
                });
            }
        });
    </script>

</body>
</html>