<?php
require_once '../../connection/connection.php';
require_once '../../controllers/base.php';

$pdo = $dbConnection->getConnection();
$userManager = new UserManager($pdo);
$erreurMessage = '';
$successMessage = '';

$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id !== null) {
    // Récupérer les informations de l'utilisateur existant
    $vendor = $userManager->getUserById($id);

    // Vérifier si un formulaire POST a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données du formulaire
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $role = 'vendor';

        // Vérification de la validité des champs
        if (empty($name) || empty($email) || empty($password) || empty($role)) {
            $erreurMessage = "Tous les champs sont obligatoires.";
        }

        // Si aucune erreur, hacher le mot de passe et mettre à jour l'utilisateur
        if (empty($erreurMessage)) {
            // Hachage du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Appel à la méthode de mise à jour, en incluant l'ID de l'utilisateur
            if ($userManager->updateUser($id, $email, $name, $hashedPassword, $role)) {
                $successMessage = "Les informations du vendeur ont été mises à jour avec succès.";
                // Redirection vers la page d'accueil ou la liste des vendeurs
                header('Location: http://localhost:3000/view/vendor.php');
                exit; // Arrêter l'exécution après la redirection
            } else {
                $erreurMessage = "Erreur lors de la mise à jour du vendeur.";
            }
        }
    }
}
?>


















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
            <input type="text" id="name" placeholder="Enter your name" name="name" value="<?php echo $vendor['nom']; ?>"
                class="mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-gray-50 transition duration-200"  required/>
        </div>

        <div class="flex flex-col">
            <label for="email" class="text-gray-700 text-sm font-semibold">Email</label>
            <input type="email" id="email" placeholder="Enter your email" name="email" value="<?php echo $vendor['email']; ?>"
                class="mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-gray-50 transition duration-200" required />
        </div>

        <div class="flex flex-col">
            <label for="password" class="text-gray-700 text-sm font-semibold">Password</label>
            <input type="password" id="password" placeholder="Enter your password" name="password"  value="<?php echo $vendor['password']; ?>"
                class="mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-gray-50 transition duration-200" required />
        </div>
        <div class="flex flex-col">
            <label for="name" class="text-gray-700 text-sm font-semibold">Role</label>
            <input type="text" id="role" placeholder="Enter your name" name="role" value="<?php echo $vendor['role']; ?>"
                class="mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-gray-50 transition duration-200"  required/>
        </div>
        <button type="submit"
            class="mt-6 px-6 py-3 w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-md transition-colors duration-200">
            Submit
        </button>


    </form>

</body>
</html>
