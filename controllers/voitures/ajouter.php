<?php
// Inclure la connexion à la base de données et la classe VoitureCRUD
require_once '../../connection/connection.php';
require_once '../base.php';

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $matricule = $_POST['matricule'];
    $modele = $_POST['modele'];
    $marque = $_POST['marque'];
    $annee = $_POST['annee'];
    

    // Créer une instance de VoitureCRUD pour insérer la voiture
    $pdo = $dbConnection->getConnection(); // Connexion PDO à la base de données
    $voitureCRUD = new VoitureCRUD($pdo); // Création d'une instance de VoitureCRUD

    // Tableau des données à insérer
    $data = [
        'NumImmatriculation'=>$matricule,
        'Model' => $modele,
        'Marque' => $marque,
        'Annee' => $annee,
        
    ];

    // Appel de la méthode create() pour insérer les données dans la base
    $isCreated = $voitureCRUD->create($data);

    // Vérifier si l'insertion a réussi
    if ($isCreated) {
        // Rediriger vers la page des voitures après l'ajout réussi
        header('Location: http://localhost:3000/view/voiture.php');
        exit();
    } else {
        // Si une erreur survient, afficher un message d'erreur
        $errorMessage = "Erreur lors de l'ajout de la voiture. Veuillez réessayer.";
    }
}


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Voiture - Dashboard Admin</title>
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
                <h1 class="text-4xl font-semibold text-gray-800">Ajouter une Voiture</h1>
            </div>

            <p class="text-xl text-gray-600 mb-6">Veuillez remplir les informations ci-dessous pour ajouter une nouvelle voiture.</p>

            <?php if (isset($errorMessage)): ?>
                <div class="bg-red-500 text-white p-4 mb-6 rounded-lg">
                    <?= htmlspecialchars($errorMessage); ?>
                </div>
            <?php endif; ?>

            <!-- Formulaire d'ajout de voiture -->
            <form method="POST" class="space-y-6">
            <div>
                    <label for="modele" class="block text-lg font-medium text-gray-700">Matricule</label>
                    <input type="text" name="matricule" id="matricule" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="modele" class="block text-lg font-medium text-gray-700">Modèle</label>
                    <input type="text" name="modele" id="modele" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="marque" class="block text-lg font-medium text-gray-700">Marque</label>
                    <input type="text" name="marque" id="marque" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="annee" class="block text-lg font-medium text-gray-700">Année</label>
                    <input type="number" name="annee" id="annee" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-lg hover:bg-blue-700 transition">
                        Ajouter la voiture
                    </button>
                </div>
            </form>
        </div>

    </div>

</body>

</html>

