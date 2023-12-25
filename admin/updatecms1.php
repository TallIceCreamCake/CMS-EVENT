<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    // Assurez-vous que le nom a été fourni
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        // Récupère le nouveau nom depuis le formulaire
        $newName = $_POST['name'];
        $config_file = __DIR__ . '/../config.json';
        $config_data = json_decode(file_get_contents($config_file), true);
        $config_data['name'] = $newName;
        file_put_contents($config_file, json_encode($config_data, JSON_PRETTY_PRINT));
        header("Location: ./index.php");
        exit();
    } else {
        // Affiche un message d'erreur si le nom n'est pas fourni
        echo "Veuillez fournir un nouveau nom.";
    }
}
?>
