<?php
require './authentification/authModel.php';
require './connection/connection.php';

$pdo = $dbConnection->getConnection();

$role = isset($_GET['role']) ? $_GET['role'] : null;
$name = isset($_GET['name']) ? $_GET['name'] : null;


if ($role !== 'admin' && $role !== 'vendor') {
    header('Location: http://localhost:3000/authentification/login.php');
    exit;
}


$roleClass = $role; 
$roleInstance = new $roleClass($pdo);

// Vérifier si l'utilisateur est connecté pour ce rôle
if (!$roleInstance->isLoggedIn()) {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de login
    header('Location: http://localhost:3000/authentification/login.php');
    exit;
}

// Récupérer les informations de l'utilisateur connecté
$user = $roleInstance->getUser();

// Vous pouvez maintenant utiliser la variable $user pour afficher les informations de l'utilisateur

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Location de Voitures</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex h-screen">
        <div class="w-64 bg-gray-800 text-white p-6">
            <h2 class="text-3xl font-bold mb-8 text-center">Admin Dashboard</h2>
            <ul class="space-y-6">
                <li><a href="./index.php" class="block py-3 px-6 text-xl rounded-lg hover:bg-gray-700">Home</a></li>
                <li><a href="./controllers/voiture.php" class="block py-3 px-6 text-xl rounded-lg hover:bg-gray-700">Voitures</a></li>
                <li><a href="./controllers//voiture.php" class="block py-3 px-6 text-xl rounded-lg hover:bg-gray-700">Contrats</a></li>
                <li><a href="./controllers/clients.php" class="block py-3 px-6 text-xl rounded-lg hover:bg-gray-700">Clients</a></li>
                <?php
                if (isset($user)) {
                    if ($roleClass === 'admin') {
                        echo '<li><a href="/view/vendor.php?name=' . urlencode($user['nom']) . '" class="block py-3 px-6 text-xl rounded-lg hover:bg-gray-700">Vendeuses</a></li>';
                    }
                }
                ?>

            </ul>
            <div class="mt-auto">
            <a href="./authentification/logout.php?role=<?php echo $roleClass; ?>" class="block py-3 px-6 bg-red-600 text-lg rounded-lg text-center hover:bg-red-700 mt-6">Déconnexion</a>
            </div>
        </div>

        <div class="flex-1 p-8 bg-gray-50">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-4xl font-semibold text-gray-800"><?php echo "welcome  {$name} " ?></h1>
                <!-- <button class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-lg hover:bg-green-700 transition">Ajouter un client</button> -->
            </div>

            <p class="text-xl text-gray-600 mb-6">Gérez vos voitures, contrats et clients en un seul endroit.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-lg transition hover:shadow-2xl">
                    <h3 class="text-2xl font-semibold text-gray-800">Voitures</h3>
                    <p class="text-lg text-gray-600 mt-3">10 véhicules enregistrés</p>
                    <div class="mt-4 text-right">
                        <a href="controllers/cars.php" class="text-blue-600 hover:text-blue-800">Voir plus →</a>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-lg transition hover:shadow-2xl">
                    <h3 class="text-2xl font-semibold text-gray-800">Contrats</h3>
                    <p class="text-lg text-gray-600 mt-3">5 contrats en cours</p>
                    <div class="mt-4 text-right">
                        <a href="controllers/contracts.php" class="text-blue-600 hover:text-blue-800">Voir plus →</a>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-lg transition hover:shadow-2xl">
                    <h3 class="text-2xl font-semibold text-gray-800">Clients</h3>
                    <p class="text-lg text-gray-600 mt-3">20 clients actifs</p>
                    <div class="mt-4 text-right">
                        <a href="controllers/clients.php" class="text-blue-600 hover:text-blue-800">Voir plus →</a>
                    </div>
                </div>
            </div>


            <div class="mt-12 bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-3xl font-semibold text-gray-800 mb-4">Dernières Activités</h2>
                <ul class="space-y-4">
                    <li class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center text-xl">A</div>
                        <div>
                            <p class="text-lg text-gray-800">Ajout de la voiture Audi A6</p>
                            <p class="text-sm text-gray-500">Il y a 3 heures</p>
                        </div>
                    </li>
                    <li class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center text-xl">C</div>
                        <div>
                            <p class="text-lg text-gray-800">Création d'un nouveau contrat</p>
                            <p class="text-sm text-gray-500">Il y a 1 jour</p>
                        </div>
                    </li>
                    <li class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center text-xl">V</div>
                        <div>
                            <p class="text-lg text-gray-800">Mise à jour de l'état de la voiture</p>
                            <p class="text-sm text-gray-500">Il y a 2 jours</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</body>

</html>