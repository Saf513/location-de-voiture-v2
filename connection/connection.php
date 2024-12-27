<?php
include 'DatabaseConnection.php';

$host = 'localhost';       
$dbname = 'locationdb';    
$username = 'root';  
$password = 'root';  

$dbConnection = new DatabaseConnection($host, $dbname, $username, $password);
$pdo = $dbConnection->getConnection();

if (!$pdo) {
    echo "La connexion à la base de données a échoué.";
} 
