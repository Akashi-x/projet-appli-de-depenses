<?php
require_once "../config/config.php";

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM operation WHERE ID_OPERATIONS_ = ? AND TYPE_OPERATION = 'REVENU'");
    $stmt->execute([$id]);
}
header("Location: liste_rentree.php");
exit;
