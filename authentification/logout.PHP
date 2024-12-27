<?php
require('../connection/connection.php');
require('../authentification/authModel.php');

$pdo = $dbConnection->getConnection();

// Check if the role is set and is a valid role
$validRoles = ['Admin', 'Vendor'];  // List of valid roles

// Get the role from URL parameters
$role = isset($_GET['role']) ? $_GET['role'] : null;

// Validate the role
if ($role !== null && in_array($role, $validRoles)) {
    // Dynamically create an instance of the correct class
    $authClass = ucfirst(strtolower($role));  // Capitalize the first letter of the role (e.g. 'admin' -> 'Admin')

    // Instantiate the correct role class
    $auth = new $authClass($pdo);
    $auth->logout();
} else {
    // If role is not valid, redirect to login or show an error
    header('Location: http://localhost:3000/authentification/login.php');
    exit;
}

// Redirect to the login page after logout
header('Location: http://localhost:3000/authentification/login.php');
exit;
?>
