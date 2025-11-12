<?php
require_once __DIR__ . '/env.php';

try {
    $mysqlClient = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;port=$DB_PORT;charset=utf8",
        $DB_USER,
        $DB_PASS,
        $DB_PORT
    );
    $mysqlClient->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
