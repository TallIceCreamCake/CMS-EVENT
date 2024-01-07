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

    $nom = $_POST['nom'];
    $idwarehouse = $_POST['stock'];
    $quantitypost = $_POST['quantity_stock'];
    $type = $_POST['type'];
    $soustype = $_POST['soustype'];
    $qrcode = generateQRCode();

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

    // Insert the material into materiel table
    if (!empty($_FILES['file']['name'])) {
        $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $uniqueFilename = uniqid('file_', true) . '.' . $fileExtension;

        $reservation_query = $db->prepare("INSERT INTO materiel (nom, type, soustype, image, qrcode) VALUES (?, ?, ?, ?, ?)");
        $reservation_query->bind_param("sssss", $nom, $type, $soustype, $uniqueFilename, $qrcode);

        if (!$reservation_query->execute()) {
            header("Location: erreur.php");
            exit();
        }

        $new_material_id = $db->insert_id;

        // Insert material into warehouse_stock table
        $stock_query = $db->prepare("INSERT INTO warehouse_stock (id_warehouse, id_equipment, nb_stockequipment) VALUES (?, ?, ?)");
        $stock_query->bind_param("sss", $idwarehouse, $new_material_id, $quantitypost);

        if (!$stock_query->execute()) {
            header("Location: erreur.php");
            exit();
        }

        // Upload the file
        $uploadDir = '../images/imagematos/';
        $uploadFile = $uploadDir . $uniqueFilename;

        if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            header("Location: erreur.php");
            exit();
        }
    } else {
        // Insert the material into materiel table without image
        $reservation_query = $db->prepare("INSERT INTO materiel (nom, type, soustype, qrcode) VALUES (?, ?, ?, ?)");
        $reservation_query->bind_param("ssss", $nom, $type, $soustype, $qrcode);

        if (!$reservation_query->execute()) {
            header("Location: erreur.php");
            exit();
        }

        $new_material_id = $db->insert_id;

        // Insert material into warehouse_stock table
        $stock_query = $db->prepare("INSERT INTO warehouse_stock (id_warehouse, id_equipment, nb_stockequipment) VALUES (?, ?, ?)");
        $stock_query->bind_param("sss", $idwarehouse, $new_material_id, $quantitypost);

        if (!$stock_query->execute()) {
            header("Location: erreur.php");
            exit();
        }
    }

    // Log the event in logs_warehouse table
    $legend = "Matériel ajouté dans l'entrepôt : $nom (QT : $quantitypost)";
    $date = date('Y-m-d');
    $time = date('H:i:s');

    $log_query = $db->prepare("INSERT INTO logs_warehouse (id_user, id_warehouse, text, date, time) VALUES (?, ?, ?, ?, ?)");
    $log_query->bind_param("issss", $user_id, $idwarehouse, $legend, $date, $time);
    $log_query->execute();

    $log_query->close();

    // Redirect the user after processing
    header("Location: index.php");
    exit();
} else {
    header("Location: erreur.php");
    exit();
}
?>
