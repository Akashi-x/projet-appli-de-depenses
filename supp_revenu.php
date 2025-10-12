<?php
require_once "config/config.php";

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $mysqlClient->prepare("DELETE FROM operation WHERE ID_OPERATIONS_ = ? AND ID_UTILISATEUR = ?");
    $stmt->execute([$id, 5]);
}
header("Location: revenus.php");
exit;
