<?php
require_once 'config/env.php';
try {
    $mysqlClient = new PDO(
        "mysql:host=localhost;port=3307;dbname=gestion_depenses;charset=utf8",
        "root",
        ""
    );
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>