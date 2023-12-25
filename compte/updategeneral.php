<?php
session_start();

// Vérifiez si l'utilisateur est connecté
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
// Vérifiez si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérez les données du formulaire
    $email = $_POST['email'];
    $username = $_POST['username'];
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];

    // Utilisez la session pour récupérer le nom d'utilisateur
    $session_username = $_SESSION['username'];

    // Mettez à jour les informations dans la base de données
    $update_query = $db->prepare("UPDATE utilisateur SET mail = ?, nom_utilisateur = ?, prenom = ?, nom = ? WHERE nom_utilisateur = ?");
    $update_query->bind_param("sssss", $email, $username, $prenom, $nom, $session_username);
    
    // Exécutez la requête
    if ($update_query->execute()) {
        // Mise à jour réussie
        // Mettez à jour le nom d'utilisateur dans la session
        $_SESSION['username'] = $username;

        header("Location: index.php?success=2");
        exit();
    } else {
        // Erreur lors de la mise à jour
        echo "Erreur lors de la mise à jour des informations.";
    }

    // Fermez la connexion à la base de données
    $db->close();
}
?>
