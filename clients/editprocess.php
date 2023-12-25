<?php
session_start();

if (empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['clients_id'])) {
        $client_id = $_POST['clients_id'];

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
        $prenom = $_POST['prenom'];
        $mail = $_POST['mail'];
        $mail2 = $_POST['mail2']; // Correction ici
        $entreprise = $_POST['entreprise'];
        $phone = $_POST['phone'];
        $phone2 = $_POST['phone2']; // Correction ici

        // Ajoutez des vérifications pour les champs vides si nécessaire

        // Requête pour mettre à jour la réservation dans la base de données
        $query = $db->prepare("UPDATE clients SET nom = ?, prenom = ?, entreprise = ?, mail = ?, mail2 = ?, phone = ?, phone2 = ? WHERE id = ?");
        $query->bind_param("sssssssi", $nom, $prenom, $entreprise, $mail, $mail2, $phone, $phone2, $client_id);

        if ($query->execute()) {
            // Redirigez vers la page de détails de la réservation mise à jour
            header("Location: details.php?clients_id=" . $client_id);
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
