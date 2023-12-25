<?php
session_start();

if (isset($_GET['deconnexion'])) {
    if ($_GET['deconnexion'] == true) {
        session_unset();
        header("location:../login.php");
    }
}

if (empty($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

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

// Fonction pour enregistrer une image
function saveImage($inputName, $targetPath)
{
    if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] == UPLOAD_ERR_OK) {
        $tempName = $_FILES[$inputName]['tmp_name'];
        move_uploaded_file($tempName, $targetPath);
        return true;
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Enregistrement du logo s'il est présent
    if (saveImage('logo_input', __DIR__ . '/../images/logo.png')) {
        echo 'Logo enregistré avec succès.';
        header('Location: ./index.php');
    } else {
        echo 'Aucun logo à enregistrer.';
        header('Location: ./index.php');
    }

    // Enregistrement de la bannière s'elle est présente
    if (saveImage('banniere_input', __DIR__ . '/../images/banniere.png')) {
        echo 'Bannière enregistrée avec succès.';
        header('Location: ./index.php');
    } else {
        echo 'Aucune bannière à enregistrer.';
        header('Location: ./index.php');
    }
}
?>
