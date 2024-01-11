<?php
session_start();
$config_file = __DIR__ . '/config.json';
$config_data = json_decode(file_get_contents($config_file), true);
if ($config_data['isconfig?'] === "false") {
    header("Location: ./installation");
  } else {
$db_host = $config_data['db_host'];
$db_username = $config_data['db_username'];
$db_password = $config_data['db_password'];
$db_name = $config_data['db_name'];
$db = new mysqli($db_host, $db_username, $db_password, $db_name);
}
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <script src="./components/tailwind.js"></script>
</head>

<body class="bg-gray-200 h-screen flex justify-center items-center">
    <div class="bg-white p-8 rounded-lg shadow-md">
        <img class="mx-auto mb-4 rounded-full" src="./logo.jpg" alt="Logo" width="100" height="100">
        <h1 class="text-2xl font-semibold text-center mb-4">Connexion</h1>
        <form action="verification.php" method="POST" class="space-y-4">
            <div class="space-y-2">
                <label for="username" class="block text-gray-600">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" class="w-full px-3 py-2 pr-[5rem] border rounded-lg"
                    autofocus required>
            </div>
            <div class="space-y-2">
                <label for="password" class="block text-gray-600">Mot de passe</label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded-lg"
                    required>
            </div>
            <button type="submit" id="submit"
                class="w-full bg-blue-500 text-white rounded-lg px-4 py-2 hover:bg-blue-600">CONNEXION</button>
        </form>
        <?php
            if(isset($_GET['erreur'])){
                $err = $_GET['erreur'];
                if($err == 1 || $err == 2) {
                    echo '<div class="text-red-500 mt-4">Mot de passe ou identifiant incorrect</div>';
                }
            }
        ?>
    </div>
</body>

</html>
</div>
</body>

</html>