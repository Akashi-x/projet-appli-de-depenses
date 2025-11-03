<?php
require_once 'config/env.php';

try {
    $mysqlClient = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8",
        $DB_USER,
        $DB_PASS
    );
    $mysqlClient->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
