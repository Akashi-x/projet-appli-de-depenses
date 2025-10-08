<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    // Rediriger vers la page de connexion
    header("Location: connexion.php");
    exit();
}

// Empêcher le cache pour éviter le retour via bouton arrière
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
