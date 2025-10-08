<?php
require_once "config.php"; 

$message = "";
try {
    $sql = "SELECT ID_TYPE, NOM_TYPE FROM TYPE";
    $stmt = $pdo->query($sql);
    $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors du chargement des types : " . $e->getMessage());
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom_categorie"]);
    $id_type = (int) $_POST["id_type"];

    if (!empty($nom) && $id_type > 0) {
        try {
            $sql = "INSERT INTO CATEGORIE (NOM_CATEGORIE, ID_TYPE) VALUES (:nom, :id_type)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ":nom" => $nom,
                ":id_type" => $id_type
            ]);

            $message = "<p style='color:green;'> Catégorie ajoutée avec succès !</p>";
        } catch (PDOException $e) {
            $message = "<p style='color:red;'>Erreur : " . $e->getMessage() . "</p>";
        }
    } else {
        $message = "<p style='color:red;'> Veuillez remplir tous les champs.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Ajouter une catégorie</title>
        <link rel="stylesheet" href="../CSS/stylecategorie.css">
    </head>
    <body>
        <div class="container">
            <h2>Ajouter une catégorie</h2>
            <?php if (!empty($message)) echo $message; ?>
            <form method="POST" action="">
                <label for="nom_categorie">Nom de la catégorie</label>
                <input type="text" id="nom_categorie" name="nom_categorie" placeholder="ex: Alimentation"><br><br>
                 <label>Type :</label>
    <select name="id_type" required>
        <option value="">-- Sélectionner --</option>
        <option value="1">Revenu</option>
        <option value="2">Dépense</option>
    </select>
                <div class="btn-container">
                    <button type="submit">ENVOYER</button>
                    <button type="reset">ANNULER</button>
                </div>
            </form>
        </div>
    </body>
</html>