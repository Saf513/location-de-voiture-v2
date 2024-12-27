<?php
require '../authentification/authModel.php';
require '../connection/connection.php';
$pdo = $dbConnection->getConnection();
$auth = new Auth($pdo);

if (!$auth->isLoggedIn()) {
    header('Location:http://localhost:3000/authentification/login.php');
    exit;
}
$user = $auth->getUser();

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
                    if ($user['role'] === 'admin') {
                        echo '<li><a href="./controllers/vendor.php" class="block py-3 px-6 text-xl rounded-lg hover:bg-gray-700">Vendeures</a></li>';
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
                <h1 class="text-4xl font-semibold text-gray-800"><?php echo "welcome  {$user['nom']} " ?></h1>
                <button class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-lg hover:bg-green-700 transition"><a href="../controllers/vendeurs/ajouter.php">Ajouter un vendeur</a></button>
            </div>

            <p class="text-xl text-gray-600 mb-6">Gérez vos voitures, contrats et clients en un seul endroit.</p>

            <div class="font-[sans-serif] overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-800 whitespace-nowrap">
                        <tr>
                            <th class="p-4 text-left text-sm font-medium text-white">
                                Name
                            </th>
                            <th class="p-4 text-left text-sm font-medium text-white">
                                Email
                            </th>
                            <th class="p-4 text-left text-sm font-medium text-white">
                                Role
                            </th>
                            <th class="p-4 text-left text-sm font-medium text-white">
                                Joined At
                            </th>
                            <th class="p-4 text-left text-sm font-medium text-white">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="whitespace-nowrap">
                        <tr class="even:bg-blue-50">
                            <td class="p-4 text-sm text-black">
                                John Doe
                            </td>
                            <td class="p-4 text-sm text-black">
                                john@example.com
                            </td>
                            <td class="p-4 text-sm text-black">
                                Admin
                            </td>
                            <td class="p-4 text-sm text-black">
                                2022-05-15
                            </td>
                            <td class="p-4">
                                <button class="mr-4" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 fill-blue-500 hover:fill-blue-700"
                                        viewBox="0 0 348.882 348.882">
                                        <path
                                            d="m333.988 11.758-.42-.383A43.363 43.363 0 0 0 304.258 0a43.579 43.579 0 0 0-32.104 14.153L116.803 184.231a14.993 14.993 0 0 0-3.154 5.37l-18.267 54.762c-2.112 6.331-1.052 13.333 2.835 18.729 3.918 5.438 10.23 8.685 16.886 8.685h.001c2.879 0 5.693-.592 8.362-1.76l52.89-23.138a14.985 14.985 0 0 0 5.063-3.626L336.771 73.176c16.166-17.697 14.919-45.247-2.783-61.418zM130.381 234.247l10.719-32.134.904-.99 20.316 18.556-.904.99-31.035 13.578zm184.24-181.304L182.553 197.53l-20.316-18.556L294.305 34.386c2.583-2.828 6.118-4.386 9.954-4.386 3.365 0 6.588 1.252 9.082 3.53l.419.383c5.484 5.009 5.87 13.546.861 19.03z"
                                            data-original="#000000" />
                                        <path
                                            d="M303.85 138.388c-8.284 0-15 6.716-15 15v127.347c0 21.034-17.113 38.147-38.147 38.147H68.904c-21.035 0-38.147-17.113-38.147-38.147V100.413c0-21.034 17.113-38.147 38.147-38.147h131.587c8.284 0 15-6.716 15-15s-6.716-15-15-15H68.904C31.327 32.266.757 62.837.757 100.413v180.321c0 37.576 30.571 68.147 68.147 68.147h181.798c37.576 0 68.147-30.571 68.147-68.147V153.388c.001-8.284-6.715-15-14.999-15z"
                                            data-original="#000000" />
                                    </svg>
                                </button>
                                <button class="mr-4" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 fill-red-500 hover:fill-red-700" viewBox="0 0 24 24">
                                        <path
                                            d="M19 7a1 1 0 0 0-1 1v11.191A1.92 1.92 0 0 1 15.99 21H8.01A1.92 1.92 0 0 1 6 19.191V8a1 1 0 0 0-2 0v11.191A3.918 3.918 0 0 0 8.01 23h7.98A3.918 3.918 0 0 0 20 19.191V8a1 1 0 0 0-1-1Zm1-3h-4V2a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v2H4a1 1 0 0 0 0 2h16a1 1 0 0 0 0-2ZM10 4V3h4v1Z"
                                            data-original="#000000" />
                                        <path d="M11 17v-7a1 1 0 0 0-2 0v7a1 1 0 0 0 2 0Zm4 0v-7a1 1 0 0 0-2 0v7a1 1 0 0 0 2 0Z"
                                            data-original="#000000" />
                                    </svg>
                                </button>
                            </td>
                        </tr>


                    </tbody>
                </table>
            </div>
        </div>

</body>

</html>