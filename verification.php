<?php
session_start();
if(isset($_POST['username']) && isset($_POST['password']))
{
$config_file = __DIR__ . '/config.json';
$config_data = json_decode(file_get_contents($config_file), true);
$db_host = $config_data['db_host'];
$db_username = $config_data['db_username'];
$db_password = $config_data['db_password'];
$db_name = $config_data['db_name'];
$db = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}$username = mysqli_real_escape_string($db,htmlspecialchars($_POST['username'])); 
 $password = mysqli_real_escape_string($db,htmlspecialchars($_POST['password']));
 if($username !== "" && $password !== "") {
 $requete = "SELECT count(*) FROM utilisateur where 
 nom_utilisateur = '".$username."' and mot_de_passe = '".$password."' ";
 $exec_requete = mysqli_query($db,$requete);
 $reponse = mysqli_fetch_array($exec_requete);
 $count = $reponse['count(*)'];
 if($count!=0) {
 $_SESSION['username'] = $username;
 header('Location: index.php');
 } else {
 header('Location: login.php?erreur=1');
 }
 } else {
 header('Location: login.php?erreur=2');
 }
} else {
 header('Location: login.php');
}
mysqli_close($db);
?>