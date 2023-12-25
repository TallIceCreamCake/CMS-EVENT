<?php
session_start();

if (empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assurez-vous de valider et nettoyer les données du formulaire avant de les utiliser
    $warehouse_id = $_POST['warehouse_id']; // Corrected variable name
    $new_name = $_POST['nom'];

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

    // Get the user ID from the session (assuming user_id is stored in session)
    $username = $_SESSION['username'];
    $user_query = $db->prepare("SELECT id FROM utilisateur WHERE nom_utilisateur = ?");
    $user_query->bind_param("s", $username);
    $user_query->execute();
    $user_query->bind_result($user_id);
    $user_query->fetch();
    $user_query->close();

    // Retrieve the current name from the database
    $get_old_name_query = $db->prepare("SELECT nom FROM entrepot WHERE id = ?");
    $get_old_name_query->bind_param("i", $warehouse_id); // Corrected variable name
    $get_old_name_query->execute();
    $get_old_name_query->bind_result($old_name);
    $get_old_name_query->fetch();
    $get_old_name_query->close();

    // Update the name in the database
    $update_name_query = $db->prepare("UPDATE entrepot SET nom = ? WHERE id = ?");
    $update_name_query->bind_param("si", $new_name, $warehouse_id); // Corrected variable name
    
    if ($update_name_query->execute()) {
        // Name updated successfully, now log the event
        $legend = "Nom modifié : $old_name -> $new_name";
        $date = date('Y-m-d');
        $time = date('H:i:s');  


        $log_query = $db->prepare("INSERT INTO logs_warehouse (id_user, id_warehouse, text, date, time) VALUES (?, ?, ?, ?, ?)");
        $log_query->bind_param("issss", $user_id, $warehouse_id, $legend, $date, $time);
        $log_query->execute();

        $log_query->close();
    } else {
        // Une erreur s'est produite lors de la mise à jour
        header("Location: erreur.php"); // Redirigez vers une page d'erreur appropriée.
        exit();
    }

    // Redirigez l'utilisateur après le traitement
    header("Location: details.php?entrepot=" . $warehouse_id);
    exit();
}
?>
