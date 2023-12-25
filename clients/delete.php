
<?php
session_start();

if (empty($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer l'ID du matériel à supprimer depuis le formulaire
    $client_id = $_POST['id'];

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
    // Préparez et exécutez la requête de suppression
    $suppression_query = $db->prepare("DELETE FROM clients WHERE id = ?");
    $suppression_query->bind_param("i", $client_id);
    $suppression_query->execute();

    if ($suppression_query->affected_rows > 0) {
        // La suppression a réussi
        header("Location: index.php");
        exit();
    } else {
        // La suppression a échoué
        header("Location: erreur.php"); // Redirigez vers une page d'erreur appropriée.
        exit();
    }

    // Fermez la connexion à la base de données
    $db->close();
} else {
    // Redirigez l'utilisateur vers une page d'erreur si la méthode de la requête n'est pas POST.
    header("Location: erreur.php"); // Remplacez "erreur.php" par la page d'erreur souhaitée.
    exit();
}
?>
