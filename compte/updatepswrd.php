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
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];
    $password3 = $_POST['password3'];
    $session_username = $_SESSION['username'];
   
    $check_password_query = $db->prepare("SELECT mot_de_passe FROM utilisateur WHERE nom_utilisateur = ?");
    $check_password_query->bind_param("s", $session_username);
    $check_password_query->execute();
    $check_password_query->store_result();
    $check_password_query->bind_result($currentpassword);


    if ($check_password_query->num_rows > 0) {
        $check_password_query->fetch(); // Récupérez la valeur correcte du mot de passe

        // Vérifiez si le mot de passe actuel correspond
        if ($password1 === $currentpassword) {
            // Mot de passe actuel correct, vérifiez les nouveaux mots de passe
            if ($password2 === $password3) {
                // Mettez à jour le mot de passe dans la base de données
                $update_password_query = $db->prepare("UPDATE utilisateur SET mot_de_passe = ? WHERE nom_utilisateur = ?");
                $update_password_query->bind_param("ss", $password2, $session_username);

                // Exécutez la requête
                if ($update_password_query->execute()) {
                    // Mise à jour réussie
                    header("Location: index.php?success=1");
                    exit();
                } else {
                    // Erreur lors de la mise à jour
                    echo "Erreur lors de la mise à jour du mot de passe.";
                }
            } else {
                echo "Les nouveaux mots de passe ne correspondent pas.";
            }
        } else {
            echo "Mot de passe actuel incorrect.";
        }
    } else {
        echo "Erreur lors de la vérification du mot de passe actuel.";
    }

    // Fermez la connexion à la base de données
    $db->close();
}
?>