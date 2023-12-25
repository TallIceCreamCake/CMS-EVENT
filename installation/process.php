<?php
// Chemin vers le fichier JSON
$jsonFilePath = '../config.json';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $field1 = isset($_POST['field1']) ? $_POST['field1'] : '';
    $dbHost = isset($_POST['dbHost']) ? $_POST['dbHost'] : '';
    $dbName = isset($_POST['dbName']) ? $_POST['dbName'] : '';
    $dbUser = isset($_POST['dbUser']) ? $_POST['dbUser'] : '';
    $dbPassword = isset($_POST['dbPassword']) ? $_POST['dbPassword'] : '';

    // Charger le contenu actuel du JSON
    $configData = json_decode(file_get_contents($jsonFilePath), true);

    // Mettre à jour les informations dans le JSON
    $configData['name'] = $field1;
    $configData['db_host'] = $dbHost;
    $configData['db_name'] = $dbName;
    $configData['db_username'] = $dbUser;
    $configData['db_password'] = $dbPassword;
    $configData['isconfig?'] = "true";
    $configData['version'] = "0.1";

    // Enregistrer les modifications dans le fichier JSON
    file_put_contents($jsonFilePath, json_encode($configData));
}

// Connexion à la base de données
$mysqli = new mysqli($dbHost, $dbUser, $dbPassword);

// Vérifier la connexion
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Sélectionner la nouvelle base de données
$mysqli->select_db($dbName);

$dropDatabaseQuery = "DROP DATABASE IF EXISTS $dbName";
$mysqli->query($dropDatabaseQuery);

// Créer une nouvelle base de données
$createDatabaseQuery = "CREATE DATABASE $dbName";
$mysqli->query($createDatabaseQuery);

// Fermer la connexion
$mysqli->close();

// Reconnecter à la nouvelle base de données
$mysqli = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

// Vérifier la connexion
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Exécute une requête SQL à partir d'un fichier
$sqlFile = './template.sql';
$sqlContent = file_get_contents($sqlFile);

// Exécute chaque requête dans le fichier
// Exécute chaque requête dans le fichier
$queries = explode(';', $sqlContent);

// Requêtes non vides seulement
foreach ($queries as $query) {
    $query = trim($query); // Supprimer les espaces supplémentaires
    if (!empty($query)) {
        $mysqli->query($query);
    }
}
// Récupérer les informations de l'utilisateur depuis le formulaire
$nom_utilisateur = $_POST['username'];
$mot_de_passe = $_POST['password'];
$nom = $_POST['lastName'];
$prenom = $_POST['firstName'];
$mail = $_POST['email'];

// Assurez-vous que la connexion à la base de données est active
$mysqli = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Préparez et exécutez la requête d'insertion
$query = $mysqli->prepare("INSERT INTO utilisateur (nom_utilisateur, mot_de_passe, nom, prenom, mail, type) VALUES (?, ?, ?, ?, ?, 'user')");
$query->bind_param("sssss", $nom_utilisateur, $mot_de_passe, $nom, $prenom, $mail);
$query->execute();

header("Location: success.php");

// Fermer la connexion
$mysqli->close();

?>
