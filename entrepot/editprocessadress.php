<?php
session_start();

if (empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assurez-vous de valider et nettoyer les données du formulaire avant de les utiliser
    $warehouse_id = $_POST['warehouse_id']; // Nom de variable corrigé
    $new_adress = $_POST['adress']; // Nom de variable corrigé

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

    // Get the user ID from the session (assuming user_id is stored in session)
    $username = $_SESSION['username'];
    $user_query = $db->prepare("SELECT id FROM utilisateur WHERE nom_utilisateur = ?");
    $user_query->bind_param("s", $username);
    $user_query->execute();
    $user_query->bind_result($user_id);
    $user_query->fetch();
    $user_query->close();

    // Retrieve the current address from the database
    $get_old_adress_query = $db->prepare("SELECT lieu FROM entrepot WHERE id = ?");
    $get_old_adress_query->bind_param("i", $warehouse_id);
    $get_old_adress_query->execute();
    $get_old_adress_query->bind_result($old_adress);
    $get_old_adress_query->fetch();
    $get_old_adress_query->close();

    // Update the address in the database
    $update_adress_query = $db->prepare("UPDATE entrepot SET lieu = ? WHERE id = ?");
    $update_adress_query->bind_param("si", $new_adress, $warehouse_id);
    
    if ($update_adress_query->execute()) {
        // Address updated successfully, now log the event
        $legend = "Adresse modifiée : $old_adress -> $new_adress";
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
