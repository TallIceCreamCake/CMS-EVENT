<?php
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
$sql = "SELECT * FROM utilisateur";
$result = $db->query($sql);
$users = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $user = array(
            'id' => $row['id'],
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'mail' => $row['mail'],
        );
        $users[] = $user;
    }
}
?>

<header class="bg-gray-50 mx-auto lg:ml-80">
    <nav class="bg-white border-gray-200 dark:bg-gray-900 dark:border-gray-700">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">
                    Compte de <?php echo $users[0]['prenom'] . ' ' . $users[0]['nom']; ?>
                </span>
                <div class="flex items-center space-x-3">
                    <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar"
                        class="text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-white md:dark:hover:text-blue-500 dark:focus:text-white dark:hover:bg-gray-700 md:dark:hover:bg-transparent">
                        <img id="avatarButton" type="button" data-dropdown-toggle="userDropdown"
                            data-dropdown-placement="bottom-start"
                            class="w-10 h-10 rounded-full cursor-pointer"
                            src="<?php echo $basePath; ?>images/ppclients/basic.png" alt="User dropdown">
                    </button>
                    <div id="dropdownNavbar"
                        class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                        <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                            <div><?php echo $users[0]['prenom'] . ' ' . $users[0]['nom']; ?></div>
                            <div class="font-medium truncate"><?php echo $users[0]['mail']; ?></div>
                        </div>
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="avatarButton">
                            <li>
                                <a href="<?php echo $basePath; ?>compte/"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                    Paramètre
                                </a>
                            </li>
                            <?php
                            $username = $_SESSION['username'];
                            $sql = "SELECT role FROM utilisateur WHERE nom_utilisateur = '$username'";
                            $result = $db->query($sql);

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $userRole = $row['role'];
                                if ($userRole === 'admin') {
                            ?>
                                    <li>
                                        <a href="<?php echo $basePath; ?>admin/"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                            Paramètre CMS
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>
                        </ul>
                        <div class="py-1">
                            <a href="<?php echo $basePath; ?>index.php?deconnexion=true"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                Déconnexion
                            </a>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </nav>
</header>
