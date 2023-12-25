<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Vérifier si la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si 'clients_id' est défini dans les données POST
    if (isset($_POST['clients_id'])) {
        // Récupérer et valider client_id (convertir en entier)
        $client_id = intval($_POST['clients_id']);

        // Charger la configuration de la base de données depuis config.json
        $config_file = __DIR__ . '/../config.json';
        $config_data = json_decode(file_get_contents($config_file), true);
        $db_host = $config_data['db_host'];
        $db_username = $config_data['db_username'];
        $db_password = $config_data['db_password'];
        $db_name = $config_data['db_name'];

        // Connexion à la base de données
        $db = new mysqli($db_host, $db_username, $db_password, $db_name);
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }

        // Vérifier si la configuration est définie sur false
        if ($config_data['isconfig?'] === "false") {
            header("Location: ../installation");
            exit();
        }

        // Définir les paramètres après la vérification du type de requête
        $adressnumero = $_POST['adressnumero'];
        $adressstreet = $_POST['adressstreet'];
        $adresszipcode = $_POST['adresszipcode'];
        $adresscity = $_POST['adresscity'];
        $billingadress = $_POST['billingadress'];
        $billingzipcode = $_POST['billingzipcode'];
        $billingcity = $_POST['billingcity'];

        // Préparer et lier la requête de mise à jour
        $query = $db->prepare("UPDATE clients SET addressnumero = ?, adressstreet = ?, zipcode = ?, city = ?, billingadress = ?, billingzipcode = ?, billingcity = ? WHERE id = ?");
        $query->bind_param("isissisi", $adressnumero, $adressstreet, $adresszipcode, $adresscity, $billingadress, $billingzipcode, $billingcity, $client_id);

        // Exécuter la requête
        if ($query->execute()) {
            // Rediriger vers details.php avec le client_id mis à jour
            header("Location: details.php?clients_id=" . $client_id);
            exit();
        } else {
            // Afficher un message d'erreur si la requête échoue
            echo "Erreur lors de la mise à jour de la réservation : " . $query->error;
        }

        // Fermer la requête et la connexion à la base de données
        $query->close();
        $db->close();
    }
}
?>
