<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['db_name'])) {
    if (isset($_POST['db_name']) && !empty($_POST['db_name'])) {
        $newDbName = $_POST['db_name'];
        $newDbHost = $_POST['db_host'];
        $newDbUsername = $_POST['db_username'];
        $newDbPassword = $_POST['db_password'];

        $config_file = __DIR__ . '/../config.json';
        $config_data = json_decode(file_get_contents($config_file), true);

        // Met à jour les informations
        $config_data['db_name'] = $newDbName;
        $config_data['db_host'] = $newDbHost;
        $config_data['db_username'] = $newDbUsername;
        $config_data['db_password'] = $newDbPassword;

        file_put_contents($config_file, json_encode($config_data, JSON_PRETTY_PRINT));
        header("Location: ./index.php");
        exit();
    } else {
        // Affiche un message d'erreur si le nom de la base de données n'est pas fourni
        echo "Veuillez fournir un nom de base de données.";
    }
}
?>
