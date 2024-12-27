<?php
require_once '../../connection/connection.php';
require_once '../../controllers/base.php';

$pdo = $dbConnection->getConnection();

$userManager = new UserManager($pdo);
$erreurMessage = '';
$successMessage = '';

$id= isset($_GET['id']) ? $_GET['id'] : null;
if($id !== null){

    $userManager->deleteUser($id);
    header('Location: http://localhost:3000/view/vendor.php');
}








?>