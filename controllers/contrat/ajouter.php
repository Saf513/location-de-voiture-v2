<?php
require_once '../../connection/connection.php';
require_once '../base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientID = $_POST['clientID'];
    $voitureID = $_POST['voitureID'];
    $dateDebut = $_POST['dateDebut'];
    $dateFin = $_POST['dateFin'];
    $duree=$_POST['duree'];

    $pdo = $dbConnection->getConnection();
    $contratCRUD = new ContratCRUD($pdo);

    $data = [
        'Num' => $clientID,
        'NumImmatriculation ' => $voitureID,
        'DateDebut' => $dateDebut,
        'DateFin' => $dateFin,
        'Duree'=>$duree,
    ];

    $isCreated = $contratCRUD->create($data);

    if ($isCreated) {
        header('Location: http://localhost:3000/view/contrat.php');
        exit();
    } else {
        $errorMessage = "Erreur lors de l'ajout du contrat. Veuillez réessayer.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Contrat - Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white p-6">
            <h2 class="text-3xl font-bold mb-8 text-center">Admin Dashboard</h2>
            <ul class="space-y-6">
                <li><a href="./index.php" class="block py-3 px-6 text-xl rounded-lg hover:bg-gray-700">Home</a></li>
                <li><a href="./controllers/voiture.php" class="block py-3 px-6 text-xl rounded-lg hover:bg-gray-700">Voitures</a></li>
                <li><a href="./controllers/contrat.php" class="block py-3 px-6 text-xl rounded-lg hover:bg-gray-700">Contrats</a></li>
                <li><a href="./controllers/clients.php" class="block py-3 px-6 text-xl rounded-lg hover:bg-gray-700">Clients</a></li>
            </ul>
            <div class="mt-auto">
                <a href="./authentification/logout.PHP" class="block py-3 px-6 bg-red-600 text-lg rounded-lg text-center hover:bg-red-700 mt-6">Déconnexion</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8 bg-gray-50">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-4xl font-semibold text-gray-800">Ajouter un Contrat</h1>
            </div>

            <p class="text-xl text-gray-600 mb-6">Veuillez remplir les informations ci-dessous pour ajouter un nouveau contrat.</p>

            <?php if (isset($errorMessage)): ?>
                <div class="bg-red-500 text-white p-4 mb-6 rounded-lg">
                    <?= htmlspecialchars($errorMessage); ?>
                </div>
            <?php endif; ?>

            <!-- Formulaire d'ajout de contrat -->
            <form method="POST" class="space-y-6">
                <div>
                    <label for="clientID" class="block text-lg font-medium text-gray-700">Client ID</label>
                    <input type="text" name="clientID" id="clientID" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="voitureID" class="block text-lg font-medium text-gray-700">Voiture ID</label>
                    <input type="text" name="voitureID" id="voitureID" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="dateDebut" class="block text-lg font-medium text-gray-700">Date de Début</label>
                    <input type="date" name="dateDebut" id="dateDebut" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="dateFin" class="block text-lg font-medium text-gray-700">Date de Fin</label>
                    <input type="date" name="dateFin" id="dateFin" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="dateFin" class="block text-lg font-medium text-gray-700">Duree</label>
                    <input type="num" name="duree" id="duree" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>


                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-lg hover:bg-blue-700 transition">
                        Ajouter le contrat
                    </button>
                </div>
            </form>
        </div>

    </div>

</body>

</html>
