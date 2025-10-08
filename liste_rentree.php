<?php
require_once "../config/config.php";

try {
    // Vérifier le filtre dans l'URL
    $filtre = isset($_GET['filtre']) ? $_GET['filtre'] : 'mois_courant';

    if ($filtre === 'mois_courant') {
        // Revenus du mois courant
        $sql = "SELECT O.ID_OPERATIONS_, O.DATE_OPERATION, O.MONTANT, O.DESCRIPTION, C.NOM_CATEGORIE
                FROM operation O
                JOIN categorie C ON O.ID_CATEGORIE = C.ID_CATEGORIE
                WHERE O.TYPE_OPERATION = 'REVENU'
                  AND MONTH(O.DATE_OPERATION) = MONTH(CURRENT_DATE())
                  AND YEAR(O.DATE_OPERATION) = YEAR(CURRENT_DATE())
                ORDER BY O.DATE_OPERATION DESC";
    } else {
        // Tous les revenus
        $sql = "SELECT O.ID_OPERATIONS_, O.DATE_OPERATION, O.MONTANT, O.DESCRIPTION, C.NOM_CATEGORIE
                FROM operation O
                JOIN categorie C ON O.ID_CATEGORIE = C.ID_CATEGORIE
                WHERE O.TYPE_OPERATION = 'REVENU'
                ORDER BY O.DATE_OPERATION DESC";
    }

    $stmt = $pdo->query($sql);
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
    <link rel="stylesheet" href="../CSS/style_rentre.css">
</head>
<body>
    <div class="container">
        <h2> MES REVENUS 💰 </h2><br><br>

        <div class="controls-bar">
            <div class="add-button-wrapper">
                <a href="ajouter_rentree.php"><button class="add-button">+ Ajouter</button></a>
            </div>
            <div class="tabs">
                <a href="?filtre=mois_courant">
                    <button class="tab-button <?= ($filtre === 'mois_courant') ? 'active' : '' ?>">Revenus du mois courant</button>
                </a>
                <a href="?filtre=tous">
                    <button class="tab-button <?= ($filtre === 'tous') ? 'active' : '' ?>">Tous les revenus</button>
                </a>
            </div>
        </div>

        <table>
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
                <?php if (!empty($revenus)): ?>
                    <?php foreach ($revenus as $revenu): ?>
                        <tr>
                            <td><?= htmlspecialchars($revenu['DATE_OPERATION']) ?></td>
                            <td><?= htmlspecialchars($revenu['NOM_CATEGORIE']) ?></td>
                            <td><?= htmlspecialchars($revenu['DESCRIPTION']) ?></td>
                            <td><?= number_format($revenu['MONTANT'], 0, ',', ' ') ?> CFA</td>
                            <td>
                                <a href="modifier_rentree.php?id=<?= $revenu['ID_OPERATIONS_'] ?>"><button class="edit-btn">Modifier</button></a>
                                <a href="supprimer-rentree.php?id=<?= $revenu['ID_OPERATIONS_'] ?>" onclick="return confirm('Supprimer ce revenu ?')"><button class="delete-btn">Supprimer</button></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Aucun revenu trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>