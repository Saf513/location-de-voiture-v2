<?php
require_once '../../connection/connection.php';
require_once '../../authentification/authModel.php';


$admin = new Admin($pdo);
$erreurMessage = '';
$successMessage = '';

$id= isset($_GET['id']) ? $_GET['id'] : null;
if($id !== null){

    $admin->deleteUser($id);
    header('Location: http://localhost:3000/view/vendor.php');
}








?>