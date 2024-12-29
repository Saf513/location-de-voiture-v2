<?php

// ob_start();
// session_start();
require_once '../connection/connection.php';
require_once 'authModel.php';

$pdo = $dbConnection->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
//    empty a ajouter
    $stmt = $pdo->prepare("SELECT id, role, password, nom FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    

    if ($user) {
        if (password_verify($password, $user['password'])) {
            if ($user['role'] === 'admin') {
                $admin = new Admin($pdo);
                $admin->login($email,$password);
                header("Location: http://localhost:3000/index.php?role=admin&name=" . urlencode($user['nom']));
                exit;
            } elseif ($user['role'] === 'vendor') {
                $vendor = new vendor($pdo);
                $vendor->login($email,$password);
                header("Location: http://localhost:3000/index.php?role=vendor&name=" . urlencode($user['nom']));
                exit;
            } else {
                echo "<p class='text-red-500'>Rôle utilisateur non reconnu.</p>";
            }
        } else {
            echo "<p class='text-red-500'>Mot de passe incorrect.</p>";
        }
    } else {
        echo "<p class='text-red-500'>Email non trouvé.</p>";
    }
} else {
    echo "<p class='text-red-500'>Veuillez remplir tous les champs.</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans flex justify-center items-center min-h-screen">

    <form class="space-y-6 px-8 py-6 max-w-md mx-auto bg-white shadow-lg rounded-lg font-sans" method="POST">

        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Connexion</h2>

        <!-- Email Field -->
        <div class="flex flex-col">
            <label for="email" class="text-gray-700 text-sm font-semibold">Email</label>
            <input type="email" id="email" placeholder="Entrez votre email" name="email"
                class="mt-2 px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-gray-50" required />
        </div>

        <!-- Password Field -->
        <div class="flex flex-col">
            <label for="password" class="text-gray-700 text-sm font-semibold">Mot de passe</label>
            <input type="password" id="password" placeholder="Entrez votre mot de passe" name="password"
                class="mt-2 px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-gray-50" required />
        </div>

        <!-- Error Message (Optional) -->
        <div id="error-message" class="text-red-500 text-sm hidden">
            <p>Les informations saisies sont incorrectes, veuillez réessayer.</p>
        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="mt-6 w-full px-6 py-3 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
            Se connecter
        </button>

        <!-- Register Link -->
        <p class="text-center text-sm text-gray-600 mt-4">
            Pas encore de compte ? <a href="../authentification/register.php" class="text-blue-600 hover:text-blue-700">Créer un compte</a>
        </p>
    </form>

</body>

</html>