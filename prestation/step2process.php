<?php
session_start();

if (empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reservation_id'])) {
        $reservation_id = $_POST['reservation_id'];
        $step21 = "1";
        $step22 = "2";

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
        $query = $db->prepare("SELECT * FROM reservations WHERE id = ?");
        $query->bind_param("i", $reservation_id);
        $query->execute();
        $result = $query->get_result();
        $reservation_details = $result->fetch_assoc();

        if ($reservation_details && isset($reservation_details['step2'])) {
            if ($reservation_details['step2'] == "0") {
                $updatequery1 = $db->prepare("UPDATE reservations SET step2 = ? where id = ?");
                $updatequery1->bind_param("ii", $step21, $reservation_id);
                $updatequery1->execute();
                header("Location: ./details.php?reservation_id=$reservation_id");
                exit();
            } elseif ($reservation_details['step2'] == "1") {
                $updatequery2 = $db->prepare("UPDATE reservations SET step2 = ? where id = ?");
                $updatequery2->bind_param("ii", $step22, $reservation_id);
                $updatequery2->execute();
                header("Location: ./details.php?reservation_id=$reservation_id");
                exit();
            } else {
                header("Location: ./details.php?reservation_id=$reservation_id");
                exit();
            }
        } else {
            echo "Error: Reservation details not found.";
        }
        
    }
}
?>
