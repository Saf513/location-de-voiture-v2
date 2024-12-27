<?php
require('../connection/connection.php');
require('../authentification/authModel.php');

$pdo = $dbConnection->getConnection();

$admin = new Admin($pdo);  
$vendor = new Vendor($pdo);

if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];

    $user = $admin->getUser() ?: $vendor->getUser(); 

    if ($user && $user['role'] == 'admin') {
        $admin->logout();
    }
    elseif ($user && $user['role'] == 'vendor') {
        $vendor->logout();
    }

    // Supprimer l'ID de la session après la déconnexion
    unset($_SESSION['id']);
    session_destroy();  // Détruire la session
}

// Rediriger vers la page de login après la déconnexion
header('Location: http://localhost:3000/authentification/login.php');
exit;
?>
