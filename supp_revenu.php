<?php
require_once __DIR__ . '/check_session.php';
require_once __DIR__ . '/config/config.php';

$operationId = isset($_GET['id']) ? (int) $_GET['id'] : null;

if ($operationId) {
    $userId = $_SESSION['id'];
    $stmt = $mysqlClient->prepare("DELETE FROM operation WHERE ID_OPERATIONS_ = ? AND ID_UTILISATEUR = ?");
    $stmt->execute([$operationId, $userId]);
}

header("Location: revenus.php");
exit;
