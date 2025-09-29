<?php
try
{
    $mysqlClient=new PDO('mysql:host=localhost;dbname=gestion_depense;charset=utf8', 'root', '');
}
catch (Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}

?>