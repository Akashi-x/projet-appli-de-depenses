<?php
require_once __DIR__ . '/config/config.php';
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $id_utilisateur = 5;
    $id_categorie = (int)($_POST['categorie'] ?? '');
    $montant = (float)($_POST['montant'] ?? 0);
    $date_operation = $_POST['date'] ?? '';
    $description = $_POST['note'] ?? '';

   
    if ($id_utilisateur && $id_categorie && $montant && $date_operation) {
        try {
            $stmt = $mysqlClient->prepare("INSERT INTO OPERATION (ID_UTILISATEUR, ID_CATEGORIE, MONTANT, DATE_OPERATION, DESCRIPTION) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$id_utilisateur, $id_categorie, $montant, $date_operation, $description]);
            $message .= "✅ Opération ajoutée avec succès !";
        } catch (PDOException $e) {
            $message .= "❌ Erreur lors de l'ajout : " . $e->getMessage();
        }
    } else {
        $message .= "❌ Veuillez remplir tous les champs obligatoires."; 
    }
}








?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>page-revenue</title>
    <link rel="stylesheet" href="CSS/revenu.css">
</head>
<body>
    <div class="form" class="message">
    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
        <h2>Ajouter un Revenu</h2>
        <form id="form Revenu" method="post" action="">

           <div class="categorie"> <label for="CATEGORIE" class="taille">Categorie</label>
            <select id="CATEGORIE" name="categorie" required>
                <option value="">-- Sélectionner --</option>
                <option value="1">Salaire</option>
                <option value="2">Business</option>
                <option value="3">Cadeau</option>
                <option value="4">Autre</option>
            </select>
          </div>
            <label for="montant">Montant </label>
            <input type="number" id="montant" name="montant"  placeholder="Ex: 1000">

            <label for="date">Date</label>
            <input type="date" id="date" name="date" max="<?php echo date('Y-m-d'); ?>" required>

            <label for="note">Description</label>
            <textarea id="note" name="note" rows="3" placeholder="Ex: salaire"></textarea>

            <div class="actions">
                <button type="submit">Ajouter</button>
                <button type="reset">Annuler</button>
            </div>
        </form>
    </div>
</body>
</html>