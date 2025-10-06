
<?php
require_once __DIR__ . '/config/config.php';


$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_utilisateur = 1;
    $id_categorie = (int)($_POST['categorie'] ?? 0);
    $montant = (float)($_POST['montant'] ?? 0);
    $date_operation = $_POST['date'] ?? '';
    $description = $_POST['note'] ?? '';

    if ($id_utilisateur && $id_categorie && $montant && $date_operation) {
        try {
            $stmt = $pdo->prepare("INSERT INTO OPERATION (ID_UTILISATEUR, ID_CATEGORIE, MONTANT, DATE_OPERATION, DESCRIPTION) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$id_utilisateur, $id_categorie, $montant, $date_operation, $description]);
            $message = "✅ Opération ajoutée avec succès !";
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
    <title>page d'ajout</title>
    <link rel="stylesheet" href="CSS/page-ajout.css">
</head>
<body>

    <div class="contenu">
    <h1>Ajouter une depense</h1>
    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    <form action="" method="post">
        
        <div>>label for="Categorie">Categorie</label>
        <select name="categorie" id="categorie" required>
            <option value="">Choisir</option>
            <option value="6">Alimentation</option>
            <option value="7">Transport</option>
            <option value="5">Logement</option>
            <option value="8">Divertissement</option>          
            <option value="9">Autres</option>
      
        </select>
    </div>
  <label for="montant" >Montant</label>
        <input type="number" name="montant" placeholder="Ex: 1000" required>

        <label for="date">Date</label>
        <input type="date" id="date" name="date" max="<?php echo date('Y-m-d'); ?>" required>
        
        <label for="description">Description</label>
        <textarea name="note" id="note" placeholder="Ex: courses au supermarché" ></textarea>
        <div class="actions">
          <button type="submit">Ajouter</button>
          <button type="reset">Annuler</button>
        </div>
    </form>
    </div>
</body>
</html>