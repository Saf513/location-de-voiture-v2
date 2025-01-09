<?php
session_start();
require('../connection/connection.php');
require('../authentification/authModel.php');


$validRoles = ['Admin', 'Vendor']; 

$role = isset($_GET['role']) ? $_GET['role'] : null;
// Validate the role
if ($role !== null && in_array($role, $validRoles)) {
    $authClass = ucfirst(strtolower($role));  

    $auth = new $authClass($pdo);
    $auth->logout();
} else {
    header('Location: http://localhost:3000/authentification/login.php');
    exit;
}

header('Location: http://localhost:3000/authentification/login.php');
exit;
?>
