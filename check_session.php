<?php
session_start();

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    // Rediriger vers la page de connexion
    header("Location: index.php");
    exit();
}

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
