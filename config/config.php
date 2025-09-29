<?php
require_once 'config/env.php';
try
{
    $mysqlClient=new PDO("mysql:host=$_host;dbname=$_database;charset=utf8", $_user, $_password);
}
catch (Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}

?>