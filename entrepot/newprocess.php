<?php
session_start();

if (empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assurez-vous de valider et nettoyer les données du formulaire avant de les utiliser
    $name = $_POST['name'];
    $localisation = $_POST['localisation'];

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
    }

    // Get the user ID from the database using the username
    $username = $_SESSION['username'];
    $user_query = $db->prepare("SELECT id FROM utilisateur WHERE nom_utilisateur = ?");
    $user_query->bind_param("s", $username);
    $user_query->execute();
    $user_query->bind_result($user_id);
    $user_query->fetch();
    $user_query->close();

    $reservation_query = $db->prepare("INSERT INTO entrepot (nom, lieu) VALUES (?, ?)");
    $reservation_query->bind_param("ss", $name, $localisation);
    
    if ($reservation_query->execute()) {
        // Warehouse created successfully, now log the event
        $warehouse_id = $db->insert_id;  // Get the ID of the last inserted warehouse
        $legend = "Entrepot créé";
        $date = date('Y-m-d');
        $time = date('H:i:s');  

        $log_query = $db->prepare("INSERT INTO logs_warehouse (id_user, id_warehouse, text, date, time) VALUES (?, ?, ?, ?, ?)");
        $log_query->bind_param("issss", $user_id, $warehouse_id, $legend, $date, $time);
        $log_query->execute();

        $log_query->close();
    } else {
        // Une erreur s'est produite lors de l'insertion
        header("Location: erreur.php"); // Redirigez vers une page d'erreur appropriée.
        exit();
    }

    // Redirigez l'utilisateur après le traitement
    header("Location: ./index.php");
    exit();
}
?>
