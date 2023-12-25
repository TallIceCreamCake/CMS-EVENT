<?php
session_start();

if (empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérez les données du formulaire
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $client = $_POST['client'];
    $prix = $_POST['prix'];
    $type = $_POST['type'];
    $color = $_POST['color'];
    $adresse = $_POST['adresse'];


    // Connexion à la base de données
    $config_file = __DIR__ . '/../config.json';
    $config_data = json_decode(file_get_contents($config_file), true);
    $db_host = $config_data['db_host'];
    $db_username = $config_data['db_username'];
    $db_password = $config_data['db_password'];
    $db_name = $config_data['db_name'];
    $db = new mysqli($db_host, $db_username, $db_password, $db_name);
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Vérifier la connexion réussie
    if ($config_data['isconfig?'] === "false") {
        header("Location: ../installation");
        exit();
    }
        // Traitement du matériel sélectionné
        $selected_material = isset($_POST['material']) ? $_POST['material'] : [];
        $material_ids = implode(',', $selected_material);

        // Requête d'insertion des réservations
        $reservation_query = $db->prepare("INSERT INTO reservations (nom, description, materiel, clients, prix, type, date_debut, date_fin, color, lieu) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$reservation_query) {
            die("Erreur de préparation de la requête d'insertion: " . $db->error);
        }
        $reservation_query->bind_param("ssssdsssss", $nom, $description, $material_ids, $client, $prix, $type, $date_debut, $date_fin, $color, $adresse);

        // Vérifier si l'insertion a réussi
        if ($reservation_query->execute()) {
            // La réservation a été créée avec succès
            header("Location: index.php");
            exit();
        } else {
            // Erreur d'insertion
            die("Erreur d'insertion: " . $reservation_query->error);
        }

    // Fermez la connexion à la base de données
}
?>
