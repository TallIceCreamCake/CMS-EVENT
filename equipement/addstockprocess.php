<?php
session_start();

if (empty($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function generateQRCode() {
        $length = 20;
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $qrcode = '';
        for ($i = 0; $i < $length; $i++) {
            $qrcode .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $qrcode;
    }

    $name = $_POST['matos_name'];
    $idequipment = $_POST['matos_id'];
    $idwarehouse = $_POST['stock'];
    $quantitypost = $_POST['quantity_stock'];

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

    // Check if there is an existing entry in warehouse_stock for the selected stock
    $check_query = $db->prepare("SELECT id, nb_stockequipment FROM warehouse_stock WHERE id_warehouse = ? AND id_equipment = ?");
    $check_query->bind_param("ss", $idwarehouse, $idequipment);
    $check_query->execute();
    $check_query->bind_result($existing_stock_id, $existing_quantity);
    $check_query->fetch();
    $check_query->close();

    // If an existing entry is found, update the quantity
    if ($existing_stock_id) {
        $new_quantity = $quantitypost;  // Utilisez directement la quantité fournie dans le formulaire
        $update_query = $db->prepare("UPDATE warehouse_stock SET nb_stockequipment = ? WHERE id = ?");
        $update_query->bind_param("ss", $new_quantity, $existing_stock_id);
        $update_query->execute();
        $update_query->close();
    } else {
        // Insert a new entry in warehouse_stock
        $insert_query = $db->prepare("INSERT INTO warehouse_stock (id_warehouse, id_equipment, nb_stockequipment) VALUES (?, ?, ?)");
        $insert_query->bind_param("sss", $idwarehouse, $idequipment, $quantitypost);
        $insert_query->execute();
        $insert_query->close();
    }

    // Rest of your code...

    // Log the event in logs_warehouse table
    $legend = "Equipement modifié : $name (QT : $quantitypost)";
    $date = date('Y-m-d');
    $time = date('H:i:s');

    $log_query = $db->prepare("INSERT INTO logs_warehouse (id_user, id_warehouse, text, date, time) VALUES (?, ?, ?, ?, ?)");
    $log_query->bind_param("issss", $user_id, $idwarehouse, $legend, $date, $time);
    $log_query->execute();

    $log_query->close();

    // Redirect the user after processing
    header("Location: details.php?materiel_id=" . $idequipment);
    exit();
} else {
    header("Location: erreur.php");
    exit();
}
?>
