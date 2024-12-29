<?php

require_once '../../connection/connection.php'; 
require_once '../../controllers/base.php'; 

$pdo = $dbConnection->getConnection();

if (isset($_GET['NumImmatriculation'])) {
    $numImmatriculation = $_GET['NumImmatriculation'];

    $voitureCrud = new VoitureCRUD($pdo);
    $isDeleted = $voitureCrud->delete($numImmatriculation);
    if ($isDeleted) {
       
        header('Location: http://localhost:3000/view/voiture.php'); 
        exit;
    } else {

        echo "Erreur : La voiture n'a pas pu être supprimée.";
    }
} else {
    echo "Erreur : Numéro d'immatriculation manquant.";
}
?>
