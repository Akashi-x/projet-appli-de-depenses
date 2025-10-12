<?php
require_once __DIR__ . "/config/config.php";
if (!empty($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $mysqlClient->prepare("DELETE FROM operation WHERE ID_OPERATIONS_ = ? AND ID_UTILISATEUR = ?");
    $stmt->execute([$id, 5]);
}
header("Location: depenses.php");
exit;
?>
