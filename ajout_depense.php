
<?php
require_once __DIR__ . '/config/config.php';

$message = "";
$userId = 5; // À remplacer par la session

// Récupérer les catégories de type Dépense
$catsStmt = $mysqlClient->prepare("SELECT C.* FROM categorie C JOIN type T ON T.ID_TYPE = C.ID_TYPE WHERE T.NOM_TYPE = 'Depense' ORDER BY C.NOM_CATEGORIE ASC");
$catsStmt->execute();
$categories = $catsStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_utilisateur = $userId;
    $id_categorie = (int)($_POST['categorie'] ?? 0);
    $montant = (float)($_POST['montant'] ?? 0);
    $date_operation = $_POST['date'] ?? '';
    $description = $_POST['note'] ?? '';

    if ($id_utilisateur && $id_categorie && $montant && $date_operation) {
        try {
            // Vérifier que la catégorie est de type Dépense
            $verif = $mysqlClient->prepare("SELECT 1 FROM categorie C JOIN type T ON T.ID_TYPE = C.ID_TYPE WHERE C.ID_CATEGORIE = ? AND T.NOM_TYPE = 'Depense'");
            $verif->execute([$id_categorie]);
            if (!$verif->fetch()) {
                $message = "❌ Catégorie invalide pour une dépense.";
            } else {
                $stmt = $mysqlClient->prepare("INSERT INTO OPERATION (ID_UTILISATEUR, ID_CATEGORIE, MONTANT, DATE_OPERATION, DESCRIPTION) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$id_utilisateur, $id_categorie, $montant, $date_operation, $description]);
                // Redirection vers la page des dépenses après ajout réussi
                header('Location: depenses.php');
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
    <title>Ajouter une dépense</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/inscription.css">
    <link rel="stylesheet" href="CSS/depenses.css">
    <link rel="stylesheet" href="CSS/sidebar.css">
</head>
<body>
    <form action="" method="post" id="ajout-depense">
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
        <h3>AJOUTER UNE DÉPENSE</h3>
        <hr>

        <div>
            <label for="categorie">Catégorie</label>
            <select name="categorie" id="categorie" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['ID_CATEGORIE'] ?>">
                        <?= htmlspecialchars($cat['NOM_CATEGORIE']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="montant">Montant</label>
            <input type="number" name="montant" placeholder="Ex: 1000" required>
        </div>

        <div>
            <label for="date">Date</label>
            <input type="date" id="date" name="date" max="<?php echo date('Y-m-d'); ?>" required>
        </div>

        <div>
            <label for="note">Description</label>
            <input class="desc" type="text" name="note" id="note" placeholder="Ex: courses au supermarché">
        </div>
<br>
        <div class="conf">
            <input type="submit" value="Ajouter">
            <input type="button" value="Retour" onclick="window.location.href='depenses.php'">
        </div>
        
        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

    </form>
</body>
</html>