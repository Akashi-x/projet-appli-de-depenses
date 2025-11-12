<?php
require_once __DIR__ . '/check_session.php';
require_once __DIR__ . '/config/config.php';

if (!empty($_GET['id'])) {
    $operationId = (int) $_GET['id'];
    $userId = $_SESSION['id'];

    $stmt = $mysqlClient->prepare("DELETE FROM operation WHERE ID_OPERATIONS_ = ? AND ID_UTILISATEUR = ?");
    $stmt->execute([$operationId, $userId]);
}

header("Location: depenses.php");
exit;
?>
