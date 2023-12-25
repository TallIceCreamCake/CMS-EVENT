<?php
session_start();
if (empty($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification de la présence de l'identifiant de la réservation
    if (isset($_POST['reservation_id'])) {
        // Récupérer l'identifiant de la réservation depuis le formulaire
        $reservation_id = $_POST['reservation_id'];

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
        $query = $db->prepare("DELETE FROM reservations WHERE id = ?");
        $query->bind_param("i", $reservation_id); // "i" signifie que c'est un entier
        $query->execute();

        // Vérifiez si la suppression a réussi
        if ($query->affected_rows > 0) {
            // La réservation a été supprimée avec succès
            header("Location: index.php");
            exit();
        } else {
            // Une erreur s'est produite lors de la suppression
            header("Location: erreur.php"); // Redirigez vers une page d'erreur appropriée.
            exit();
        }
    }
}

// Rediriger vers une page d'erreur si la méthode de la requête n'est pas POST ou si l'identifiant de réservation est manquant.
header("Location: erreur.php"); // Remplacez "erreur.php" par la page d'erreur souhaitée.
exit();
?>