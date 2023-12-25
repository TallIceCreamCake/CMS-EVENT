<?php
session_start();

if (empty($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérez les données du formulaire
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $mail = $_POST['mail'];
    $mail2 = $_POST['mail2'];
    $numero = $_POST['numero'];
    $numero2 = $_POST['numero2'];
    $date = $_POST['date'];
    $entreprise = $_POST['entreprise'];
    $type = $_POST['type'];
    $adressnumero = $_POST['adressnumero'];
    $adressstreet = $_POST['adressstreet'];
    $adresszipcode = $_POST['adresszipcode'];
    $adresscity = $_POST['adresscity'];
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
    // Vérifiez si un fichier a été téléchargé
    $billingadress = $adressnumero . ' ' . $adressstreet;

    if (!empty($_FILES['file']['name'])) {
        // Générez un nom de fichier unique
        $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $uniqueFilename = uniqid('file_', true) . '.' . $fileExtension;
        // Préparez la requête SQL pour l'insertion avec la colonne "pp"
        $reservation_query = $db->prepare("INSERT INTO clients (nom, prenom, entreprise, mail, mail2, phone, phone2, pp, type, addressnumero, adressstreet, zipcode, city, billingadress, billingzipcode, billingcity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $reservation_query->bind_param("ssssssiisissssss", $nom, $prenom, $entreprise, $mail, $mail2, $numero, $numero2, $uniqueFilename, $type, $adressnumero, $adressstreet, $adresszipcode, $adresscity, $billingadress, $adresszipcode, $adresscity);
        // Vérifiez si l'insertion a réussi
        if (!$reservation_query->execute()) {
            // Une erreur s'est produite lors de l'insertion
            header("Location: erreur.php"); // Redirigez vers une page d'erreur appropriée.
            exit();
        }

        // Déplacez le fichier téléchargé vers le dossier de destination
        $uploadDir = '../images/ppclients/';
        $uploadFile = $uploadDir . $uniqueFilename;

        if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            // Une erreur s'est produite lors du téléchargement du fichier
            header("Location: erreur.php"); // Redirigez vers une page d'erreur appropriée.
            exit();
        }
    } else {
        // Si aucun fichier n'est téléchargé, insérez sans mettre à jour la colonne "pp"
        $reservation_query = $db->prepare("INSERT INTO clients (nom, prenom, entreprise, mail, mail2, phone, phone2, type, addressnumero, adressstreet, zipcode, city, billingadress, billingzipcode, billingcity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $reservation_query->bind_param("sssssiisissssss", $nom, $prenom, $entreprise, $mail, $mail2, $numero, $numero2, $type, $adressnumero, $adressstreet, $adresszipcode, $adresscity, $billingadress, $adresszipcode, $adresscity);

        // Vérifiez si l'insertion a réussi
        if (!$reservation_query->execute()) {
            // Une erreur s'est produite lors de l'insertion
            header("Location: erreur.php"); // Redirigez vers une page d'erreur appropriée.
            exit();
        }
    }

    // Redirigez l'utilisateur après le téléchargement du fichier
    header("Location: index.php");
    exit();

    // Fermez la connexion à la base de données
} else {
    // Redirigez l'utilisateur vers une page d'erreur si la méthode de la requête n'est pas POST.
    header("Location: erreur.php"); // Remplacez "erreur.php" par la page d'erreur souhaitée.
    exit();
}
?>
