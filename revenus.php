<?php
require_once 'check_session.php';
require_once "config/config.php";

$userId = $_SESSION['id'];

// Récupérer les informations de l'utilisateur
$stmt = $mysqlClient->prepare("SELECT NOM_UTILISATEUR, PRENOM FROM utilisateur WHERE ID_UTILISATEUR = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

try {
    // Vérifier le filtre dans l'URL
    $filtre = isset($_GET['filtre']) ? $_GET['filtre'] : 'mois_courant';

    if ($filtre === 'mois_courant') {
        // Revenus du mois courant
        $sql = "SELECT O.ID_OPERATIONS_, O.DATE_OPERATION, O.MONTANT, O.DESCRIPTION, C.NOM_CATEGORIE
                FROM operation O
                JOIN categorie C ON O.ID_CATEGORIE = C.ID_CATEGORIE
                JOIN type T ON C.ID_TYPE = T.ID_TYPE
                WHERE T.NOM_TYPE = 'Revenu'
                  AND O.ID_UTILISATEUR = ?
                  AND MONTH(O.DATE_OPERATION) = MONTH(CURRENT_DATE())
                  AND YEAR(O.DATE_OPERATION) = YEAR(CURRENT_DATE())
                ORDER BY O.DATE_OPERATION DESC";
    } else {
        // Tous les revenus
        $sql = "SELECT O.ID_OPERATIONS_, O.DATE_OPERATION, O.MONTANT, O.DESCRIPTION, C.NOM_CATEGORIE
                FROM operation O
                JOIN categorie C ON O.ID_CATEGORIE = C.ID_CATEGORIE
                JOIN type T ON C.ID_TYPE = T.ID_TYPE
                WHERE T.NOM_TYPE = 'Revenu'
                  AND O.ID_UTILISATEUR = ?
                ORDER BY O.DATE_OPERATION DESC";
    }

    $stmt = $mysqlClient->prepare($sql);
    $stmt->execute([$userId]);
    $revenus = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer aussi tous les revenus pour l'onglet "Tous"
    $sqlTous = "SELECT O.ID_OPERATIONS_, O.DATE_OPERATION, O.MONTANT, O.DESCRIPTION, C.NOM_CATEGORIE
                FROM operation O
                JOIN categorie C ON O.ID_CATEGORIE = C.ID_CATEGORIE
                JOIN type T ON C.ID_TYPE = T.ID_TYPE
                WHERE T.NOM_TYPE = 'Revenu'
                  AND O.ID_UTILISATEUR = ?
                ORDER BY O.DATE_OPERATION DESC";
    
    $stmtTous = $mysqlClient->prepare($sqlTous);
    $stmtTous->execute([$userId]);
    $revenusTous = $stmtTous->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
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
    <link rel="stylesheet" href="CSS/dropdown.css">
    <link rel="stylesheet" href="CSS/sidebar.css">
    <link rel="stylesheet" href="CSS/pages-actions.css">
    <title>Mes Revenus</title>
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
                <li><a href="accueil.php" style="text-decoration: none;color:white"><i class="fa-solid fa-house"></i> Accueil</a></li>
                <li class="active"><a href="revenus.php" style="text-decoration: none;color:white"><i class="fa-solid fa-wallet"></i> Revenus</a></li>
                <li><a href="depenses.php" style="text-decoration: none;color:white"><i class="fa-solid fa-credit-card"></i> Dépenses</a></li>
                <li><a href="activite.php" style="text-decoration: none;color:white"><i class="fa-solid fa-chart-pie"></i> Activité</a></li>
            </ul>
            <div class="sidebar-footer">
                <a href="deconnexion.php" class="logout-sidebar">
                    <i class="fa-solid fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
        </aside>

        <!-- Main -->
        <main class="main">
            <header class="header">
                <h1>Revenus</h1> 
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
                <h1>💰 Gestion des Revenus</h1>      
                <p>Suivez et gérez tous vos revenus en toute simplicité</p>
            </section>

            <section class="actions-section">
                <div class="btn-container">
                    <a href="ajout-revenu.php" class="btn-ajouter">
                        <i class="fa-solid fa-plus"></i> Ajouter un Revenu
                    </a>
                </div>
            </section>

            <section class="filters-section">
                <div class="tabs">
                    <button class="tab-btn <?= ($filtre === 'mois_courant') ? 'active' : '' ?>" onclick="showTab('mois_courant')">
                        <i class="fa-solid fa-calendar-month"></i> Revenus du mois
                    </button>
                    <button class="tab-btn <?= ($filtre === 'tous') ? 'active' : '' ?>" onclick="showTab('tous')">
                        <i class="fa-solid fa-list"></i> Tous les revenus
                    </button>
                </div>
            </section>

            <section class="transactions">
                <h2>Liste des Revenus</h2>
                
                <!-- Contenu Revenus du mois courant -->
                <div id="mois_courant" class="tab-content" <?= ($filtre === 'tous') ? 'style="display:none"' : '' ?>>
                    <table class="transaction">
                        <thead>
                            <tr>
                                <th>Catégorie</th>
                                <th>Description</th>
                                <th>Montant</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($filtre === 'mois_courant' && !empty($revenus)): ?>
                                <?php foreach ($revenus as $revenu): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($revenu['NOM_CATEGORIE']) ?></td>
                                        <td><?= htmlspecialchars($revenu['DESCRIPTION']) ?></td>
                                        <td class="montant positif">+<?= number_format($revenu['MONTANT'], 0, ',', ' ') ?> FCFA</td>
                                        <td><?= htmlspecialchars($revenu['DATE_OPERATION']) ?></td>
                                        <td>
                                            <a href="modif_revenu.php?id=<?= $revenu['ID_OPERATIONS_'] ?>" class="btn-icon btn-modifier" title="Modifier">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>
                                            <a href="supp_revenu.php?id=<?= $revenu['ID_OPERATIONS_'] ?>" class="btn-icon btn-supprimer" title="Supprimer" onclick="return confirm('Supprimer ce revenu ?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #bbb;">Aucun revenu trouvé pour ce mois.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Contenu Tous les revenus -->
                <div id="tous" class="tab-content" <?= ($filtre === 'mois_courant') ? 'style="display:none"' : '' ?>>
                    <table class="transaction">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Catégorie</th>
                                <th>Description</th>
                                <th>Montant</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($filtre === 'tous' && !empty($revenusTous)): ?>
                                <?php foreach ($revenusTous as $revenu): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($revenu['DATE_OPERATION']) ?></td>
                                        <td><?= htmlspecialchars($revenu['NOM_CATEGORIE']) ?></td>
                                        <td><?= htmlspecialchars($revenu['DESCRIPTION']) ?></td>
                                        <td class="montant positif">+<?= number_format($revenu['MONTANT'], 0, ',', ' ') ?> FCFA</td>
                                        <td>
                                            <a href="modif_revenu.php?id=<?= $revenu['ID_OPERATIONS_'] ?>" class="btn-icon btn-modifier" title="Modifier">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>
                                            <a href="supp_revenu.php?id=<?= $revenu['ID_OPERATIONS_'] ?>" class="btn-icon btn-supprimer" title="Supprimer" onclick="return confirm('Supprimer ce revenu ?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #bbb;">Aucun revenu trouvé.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script src="JS/dropdown.js"></script>
    <script>
        function showTab(id) {
            // Recharger la page avec le bon filtre
            const url = new URL(window.location);
            url.searchParams.set('filtre', id === 'mois_courant' ? 'mois_courant' : 'tous');
            window.location.href = url.toString();
        }
    </script>
</body>
</html>