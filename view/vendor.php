<?php
require '../authentification/authModel.php';
require '../connection/connection.php';
require '../controllers/base.php';
$pdo = $dbConnection->getConnection();
$admin = new admin($pdo);
$name = isset($_GET['name']) ? $_GET['name'] : null;


// if (!$admin->isLoggedIn()) {
//     header('Location:http://localhost:3000/authentification/login.php');
//     exit;
// }
// $user = $admin->getUser();
$vendors = $admin->readAll();
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
                <li><a href="./controllers/contrat.php" class="block py-3 px-6 text-xl rounded-lg hover:bg-gray-700">Contrats</a></li>
                <li><a href="./controllers/clients.php" class="block py-3 px-6 text-xl rounded-lg hover:bg-gray-700">Clients</a></li>
                <?php
                if (isset($user)) {
                    if ($user['role'] === 'admin') {
                        echo '<li><a href="./controllers/vendor.php" class="block py-3 px-6 text-xl rounded-lg hover:bg-gray-700">Vendeurs</a></li>';
                    }
                }
                ?>
            </ul>
            <div class="mt-auto">
                <a href="./authentification/logout.PHP" class="block py-3 px-6 bg-red-600 text-lg rounded-lg text-center hover:bg-red-700 mt-6">Déconnexion</a>
            </div>
        </div>

        <div class="flex-1 p-8 bg-gray-50">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-4xl font-semibold text-gray-800"><?php echo "Welcome {$name}"; ?></h1>
                <button class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-lg hover:bg-green-700 transition">
                    <a href="../controllers/vendeurs/ajouter.php">Ajouter un vendeur</a>
                </button>
            </div>

            <p class="text-xl text-gray-600 mb-6">Gérez vos voitures, contrats et clients en un seul endroit.</p>

            <div class="font-[sans-serif] overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-800 whitespace-nowrap">
                        <tr>
                            <th class="p-4 text-left text-sm font-medium text-white">Nom</th>
                            <th class="p-4 text-left text-sm font-medium text-white">Email</th>
                            <th class="p-4 text-left text-sm font-medium text-white">Role</th>
                            <th class="p-4 text-left text-sm font-medium text-white">Date d'ajout</th>
                            <th class="p-4 text-left text-sm font-medium text-white">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="whitespace-nowrap">
                        <?php foreach ($vendors as $vendor): ?>
                            <tr class="even:bg-blue-50">
                                <td class="p-4 text-sm text-black"><?= htmlspecialchars($vendor['nom']); ?></td>
                                <td class="p-4 text-sm text-black"><?= htmlspecialchars($vendor['email']); ?></td>
                                <td class="p-4 text-sm text-black"><?= htmlspecialchars($vendor['role']); ?></td>
                                <td class="p-4 text-sm text-black"><?= htmlspecialchars($vendor['created_at']); ?></td>
                                <td class="p-4">
                                    <button class="mr-4" title="Edit">
                                       <a href="../controllers/vendeurs/editer.php?id=<?php echo $vendor['id']; ?>"> <svg xmlns="http://www.w3.org/2000/svg" class="w-5 fill-blue-500 hover:fill-blue-700"
                                             viewBox="0 0 348.882 348.882">
                                            <path d="m333.988 11.758-.42-.383A43.363 43.363 0 0 0 304.258 0a43.579 43.579 0 0 0-32.104 14.153L116.803 184.231a14.993 14.993 0 0 0-3.154 5.37l-18.267 54.762c-2.112 6.331-1.052 13.333 2.835 18.729 3.918 5.438 10.23 8.685 16.886 8.685h.001c2.879 0 5.693-.592 8.362-1.76l52.89-23.138a14.985 14.985 0 0 0 5.063-3.626L336.771 73.176c16.166-17.697 14.919-45.247-2.783-61.418zM130.381 234.247l10.719-32.134.904-.99 20.316 18.556-.904.99-31.035 13.578zm184.24-181.304L182.553 197.53l-20.316-18.556L294.305 34.386c2.583-2.828 6.118-4.386 9.954-4.386 3.365 0 6.588 1.252 9.082 3.53l.419.383c5.484 5.009 5.87 13.546.861 19.03z" />
                                        </svg></a>
                                    </button>
                                    <button class="mr-4" title="Delete">
                                       
                                    <a href="../controllers/vendeurs/supprimmer.php?id=<?php echo $vendor['id']; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 fill-red-500 hover:fill-red-700" viewBox="0 0 24 24">
                                            <path d="M19 7a1 1 0 0 0-1 1v11.191A1.92 1.92 0 0 1 15.99 21H8.01A1.92 1.92 0 0 1 6 19.191V8a1 1 0 0 0-2 0v11.191A3.918 3.918 0 0 0 8.01 23h7.98A3.918 3.918 0 0 0 20 19.191V8a1 1 0 0 0-1-1Zm1-3h-4V2a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v2H4a1 1 0 0 0 0 2h16a1 1 0 0 0 0-2ZM10 4V3h4v1Z" />
                                        </svg></a>

                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

</body>

</html>
