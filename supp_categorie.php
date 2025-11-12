<?php
require_once __DIR__ . '/check_session.php';
require_once __DIR__ . '/config/config.php';

if (!empty($_GET['id'])) {
    $idCategorie = (int) $_GET['id'];

    try {
        $stmt = $mysqlClient->prepare('DELETE FROM categorie WHERE ID_CATEGORIE = ?');
        $stmt->execute([$idCategorie]);
    } catch (PDOException $e) {
    }
}

header('Location: gestion_categorie.php');
exit;

