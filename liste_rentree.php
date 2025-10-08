<?php
require_once "config/config.php";

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
                  AND O.ID_UTILISATEUR = 5
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
                  AND O.ID_UTILISATEUR = 5
                ORDER BY O.DATE_OPERATION DESC";
    }

    $stmt = $mysqlClient->query($sql);
    $revenus = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Revenus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/depenses.css">
    <link rel="stylesheet" href="CSS/revenus.css">
    <link rel="stylesheet" href="CSS/accueil.css">
    <link rel="stylesheet" href="CSS/sidebar.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="titre">
                <img src="icone/logo.png" alt="logo" class="logo">
                <p>Gérez vos finances</p>
            </div>
            <ul>
                <li><a href="accueil.php" style="text-decoration: none;color:white"><i class="fa-solid fa-house"></i> Accueil</a></li>
                <li class="active"><a href="liste_rentree.php" style="text-decoration: none;color:white"><i class="fa-solid fa-wallet"></i> Revenus</a></li>
                <li><a href="mesdepenses.php" style="text-decoration: none;color:white"><i class="fa-solid fa-credit-card"></i> Dépenses</a></li>
                <li><a href="activite.php" style="text-decoration: none;color:white"><i class="fa-solid fa-chart-pie"></i> Activité</a></li>
            </ul>
            <div class="sidebar-footer">
                <a href="deconnexion.php" class="logout-sidebar">
                    <i class="fa-solid fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
        </aside>

        <main class="main-content">
            <h1>MES REVENUS 💰💰</h1>

            <!-- Bouton Ajouter centré -->
            <div class="btn-container">
                <a href="ajouter_rentree.php" class="btn-ajouter">+ Ajouter</a>
            </div>

            <!-- Onglets -->
            <div class="tabs">
                <button class="tab-btn <?= ($filtre === 'mois_courant') ? 'active' : '' ?>" onclick="showTab('mois_courant')">Revenus du mois courant</button>
                <button class="tab-btn <?= ($filtre === 'tous') ? 'active' : '' ?>" onclick="showTab('toutes')">Tous les revenus</button>
            </div>

            <!-- Contenu Revenus du mois courant -->
            <div id="mois_courant" class="tab-content" <?= ($filtre === 'tous') ? 'style="display:none"' : '' ?>>
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Catégorie</th>
                        <th>Description</th>
                        <th>Montant</th>
                        <th>Actions</th>
                    </tr>
                    <?php if ($filtre === 'mois_courant' && !empty($revenus)): ?>
                        <?php foreach ($revenus as $revenu): ?>
                            <tr>
                                <td><?= htmlspecialchars($revenu['DATE_OPERATION']) ?></td>
                                <td><?= htmlspecialchars($revenu['NOM_CATEGORIE']) ?></td>
                                <td><?= htmlspecialchars($revenu['DESCRIPTION']) ?></td>
                                <td class="montant positif">+<?= number_format($revenu['MONTANT'], 0, ',', ' ') ?> FCFA</td>
                                <td>
                                    <a href="modifier_rentree.php?id=<?= $revenu['ID_OPERATIONS_'] ?>" class="btn btn-modifier">Modifier</a>
                                    <a href="supprimer-rentree.php?id=<?= $revenu['ID_OPERATIONS_'] ?>" class="btn btn-supprimer" onclick="return confirm('Supprimer ce revenu ?')">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Aucun revenu trouvé pour ce mois.</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>

            <!-- Contenu Tous les revenus -->
            <div id="toutes" class="tab-content" <?= ($filtre === 'mois_courant') ? 'style="display:none"' : '' ?>>
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Catégorie</th>
                        <th>Description</th>
                        <th>Montant</th>
                        <th>Actions</th>
                    </tr>
                    <?php if ($filtre === 'tous' && !empty($revenus)): ?>
                        <?php foreach ($revenus as $revenu): ?>
                            <tr>
                                <td><?= htmlspecialchars($revenu['DATE_OPERATION']) ?></td>
                                <td><?= htmlspecialchars($revenu['NOM_CATEGORIE']) ?></td>
                                <td><?= htmlspecialchars($revenu['DESCRIPTION']) ?></td>
                                <td class="montant positif">+<?= number_format($revenu['MONTANT'], 0, ',', ' ') ?> FCFA</td>
                                <td>
                                    <a href="modifier_rentree.php?id=<?= $revenu['ID_OPERATIONS_'] ?>" class="btn btn-modifier">Modifier</a>
                                    <a href="supprimer-rentree.php?id=<?= $revenu['ID_OPERATIONS_'] ?>" class="btn btn-supprimer" onclick="return confirm('Supprimer ce revenu ?')">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Aucun revenu trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </main>
    </div>

    <script>
        function showTab(id) {
            document.getElementById('mois_courant').style.display = 'none';
            document.getElementById('toutes').style.display = 'none';
            document.getElementById(id).style.display = 'block';
            
            // Mettre à jour l'URL sans recharger la page
            const url = new URL(window.location);
            url.searchParams.set('filtre', id === 'mois_courant' ? 'mois_courant' : 'tous');
            window.history.pushState({}, '', url);
        }
    </script>
</body>
</html>