<?php
session_start();

if (empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reservation_id'])) {
        $reservation_id = $_POST['reservation_id'];

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
        if ($config_data['isconfig?'] === "false") {
            header("Location: ../installation");
            exit();
        }

        // Récupérez les données du formulaire
        $nom = $_POST['nom'];
        $description = $_POST['description'];
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
        $type = $_POST['type'];
        $client = $_POST['client'];
        $prix = $_POST['prix'];
        $color = $_POST['color'];

        // Récupérez les matériaux sélectionnés du formulaire
        $materiel = isset($_POST['material']) ? implode(",", $_POST['material']) : '';

        // Requête pour mettre à jour la réservation dans la base de données
        $query = $db->prepare("UPDATE reservations SET nom = ?, description = ?, date_debut = ?, date_fin = ?, color = ?, type = ?, clients = ?, prix = ?, materiel = ? WHERE id = ?");
        $query->bind_param("sssssssssi", $nom, $description, $date_debut, $date_fin, $color, $type, $client, $prix, $materiel, $reservation_id);

        if ($query->execute()) {
            // Redirigez vers la page de détails de la réservation mise à jour
            header("Location: details.php?reservation_id=" . $reservation_id);
            exit();
        } else {
            echo "Erreur lors de la mise à jour de la réservation : " . $db->error;
        }

        // Assurez-vous de fermer la connexion à la base de données ici
        $query->close();
        $db->close();
    }
}
?>
