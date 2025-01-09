<?php
session_start(); 

require '../../connection/connection.php';  
require '../../authentification/authModel.php';

$pdo = $dbConnection->getConnection();

$admin = new admin($pdo);
$erreurMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = 'vendor';

    if (empty($name) || empty($email) || empty($password)) {
        $erreurMessage = "Tous les champs sont obligatoires.";
    }

    if ($admin->emailExists($email)) {
        $erreurMessage = "L'email est déjà utilisé.";
    }

    // Hachage du mot de passe
    if (empty($erreurMessage)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); 

        if ($admin->createUser($email, $hashedPassword, $name, $role)) {
            $_SESSION['successMessage'] = "Le vendeur a été ajouté avec succès."; // Stocker le message de succès dans la session
            header('Location: ../../index.php');
            exit; // Arrêter l'exécution après la redirection
        } else {
            $erreurMessage = "Erreur lors de l'ajout du vendeur.";
        }
    }
}
?>









<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <form class="space-y-6 px-8 py-8 bg-white rounded-lg shadow-lg w-full max-w-md" method="POST">

        <div class="flex flex-col">
            <label for="name" class="text-gray-700 text-sm font-semibold">Name</label>
            <input type="text" id="name" placeholder="Enter your name" name="name" 
                class="mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-gray-50 transition duration-200"  required/>
        </div>

        <div class="flex flex-col">
            <label for="email" class="text-gray-700 text-sm font-semibold">Email</label>
            <input type="email" id="email" placeholder="Enter your email" name="email" 
                class="mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-gray-50 transition duration-200" required />
        </div>

        <div class="flex flex-col">
            <label for="password" class="text-gray-700 text-sm font-semibold">Password</label>
            <input type="password" id="password" placeholder="Enter your password" name="password" 
                class="mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-gray-50 transition duration-200" required />
        </div>

        <button type="submit"
            class="mt-6 px-6 py-3 w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-md transition-colors duration-200">
            Submit
        </button>


    </form>

</body>
</html>
