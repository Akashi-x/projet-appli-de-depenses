<?php
require_once __DIR__ . '/check_session.php';
require_once __DIR__ . '/config/config.php';

$userId = $_SESSION['id'];
$stmt = $mysqlClient->prepare("SELECT NOM_UTILISATEUR, PRENOM FROM utilisateur WHERE ID_UTILISATEUR = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(); 

$message = "";
try {
    $sql = "SELECT ID_TYPE, NOM_TYPE FROM TYPE";
    $stmt = $mysqlClient->query($sql);
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
            $sql = "INSERT INTO categorie (NOM_CATEGORIE, ID_TYPE) VALUES (:nom, :id_type)";
            $stmt = $mysqlClient->prepare($sql);
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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ajouter une catégorie</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
        <link rel="stylesheet" href="CSS/inscription.css">
        <link rel="stylesheet" href="CSS/stylecategorie.css">
        <link rel="stylesheet" href="CSS/sidebar.css">
         <link rel="stylesheet" href="CSS/head.css">
    <link rel="stylesheet" href="CSS/dropdown.css">
    <script src="JS/dropdown.js" defer></script>
    </head>
    <body>
        <div class="main-content">
     <header class="head"><h1>Ajout de catégorie</h1> 
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
        <form method="POST" action="" id="ajout-categorie">
              
            <h3>AJOUTER UNE CATÉGORIE</h3>
            <hr>
            <div class="in">
            <div>
                <label for="nom_categorie">Nom de la catégorie</label>
                <input type="text" id="nom_categorie" name="nom_categorie" placeholder="ex: Alimentation">
            </div>

            <div>
                <label>Type</label>
                <select name="id_type" required>
                    <option value="">-- Sélectionner --</option>
                    <option value="1">Revenu</option>
                    <option value="2">Dépense</option>
                </select>
            </div>
            <br>
            </div>
            <div class="conf">
                <input type="submit" value="Ajouter">
                <input type="button" value="Retour" onclick="window.location.href='accueil.php'">
            </div>
            <?php if (!empty($message)) echo $message; ?>
<!-- Sidebar -->
    <aside class="sidebar">
        <div  class="titre">
      <a href="accueil.php"><img src="icone/logo.png" alt="logo" class="logo" style="cursor: pointer;" ></a>
            <h1>SAMA KALPE</h1>

      </div>
      <ul>
        <li><a href="gestion_categorie.php" style="text-decoration: none;color:white"><i class="fa-solid fa-house"></i>Gestion catégories</a></li>
       
      </ul>
      <div class="sidebar-footer">
        <a href="deconnexion.php" class="logout-sidebar">
          <i class="fa-solid fa-sign-out-alt"></i> Déconnexion
        </a>
      </div>
    </aside>
        </form>
        </div>
    </body>
</html>