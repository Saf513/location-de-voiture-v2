<?php
require_once '../../connection/connection.php'; 
require_once '../../controllers/base.php'; 

// Récupération de la connexion PDO
$pdo = $dbConnection->getConnection();

// Vérification si un NumImmatriculation est passé en paramètre
if (isset($_GET['NumImmatriculation'])) {
    $numImmatriculation = $_GET['NumImmatriculation'];
    
    // Instance de la classe VoitureCRUD
    $voitureCrud = new VoitureCRUD($pdo);

    // Récupérer les informations de la voiture à modifier
    $voiture = $voitureCrud->getByNumImmatriculation($numImmatriculation);
    
    if (!$voiture) {
        echo "Erreur : Voiture non trouvée.";
        exit;
    }
} else {
    echo "Erreur : Numéro d'immatriculation manquant.";
    exit;
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $matricule = $numImmatriculation;
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $annee = $_POST['annee'];

    // Appel de la méthode de mise à jour
    $isUpdated = $voitureCrud->update($numImmatriculation, $matricule, $marque, $modele, $annee);

    if ($isUpdated) {
        header('Location: voiture.php'); // Rediriger vers la page des voitures après modification
        exit;
    } else {
        echo "Erreur : La voiture n'a pas pu être modifiée.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Voiture - Dashboard Admin</title>
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
                <a href="./authentification/logout.php" class="block py-3 px-6 bg-red-600 text-lg rounded-lg text-center hover:bg-red-700 mt-6">Déconnexion</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8 bg-gray-50">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-4xl font-semibold text-gray-800">Modifier une Voiture</h1>
            </div>

            <p class="text-xl text-gray-600 mb-6">Veuillez modifier les informations de la voiture ci-dessous.</p>

            <?php if (isset($errorMessage)): ?>
                <div class="bg-red-500 text-white p-4 mb-6 rounded-lg">
                    <?= htmlspecialchars($errorMessage); ?>
                </div>
            <?php endif; ?>

            <!-- Formulaire de modification de voiture -->
            <form method="POST" class="space-y-6">
                <!-- Matricule -->
                <div>
                    <label for="matricule" class="block text-lg font-medium text-gray-700">Matricule</label>
                    <input type="text" name="matricule" id="matricule" value="<?= htmlspecialchars($voiture['NumImmatriculation']); ?>" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Modèle -->
                <div>
                    <label for="modele" class="block text-lg font-medium text-gray-700">Modèle</label>
                    <input type="text" name="modele" id="modele" value="<?= htmlspecialchars($voiture['Model']); ?>" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Marque -->
                <div>
                    <label for="marque" class="block text-lg font-medium text-gray-700">Marque</label>
                    <input type="text" name="marque" id="marque" value="<?= htmlspecialchars($voiture['Marque']); ?>" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Année -->
                <div>
                    <label for="annee" class="block text-lg font-medium text-gray-700">Année</label>
                    <input type="number" name="annee" id="annee" value="<?= htmlspecialchars($voiture['Annee']); ?>" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-lg hover:bg-blue-700 transition">
                        Modifier la voiture
                    </button>
                </div>
            </form>
        </div>

    </div>

</body>

</html>
