<?php
session_start();
if(isset($_GET['deconnexion']))
{ 
    if($_GET['deconnexion'] == true)
    { 
        session_unset();
        header("location:../login.php");
    }
}
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
$local_version = $config_data['version'];
$github_version_url = 'https://raw.githubusercontent.com/TallIceCreamCake/CMS-EVENT/main/config.json';

// Check if there is an internet connection
if (checkInternetConnection()) {
    // Get GitHub version
    try {
        $github_version_data = json_decode(file_get_contents($github_version_url), true);
        $github_version = $github_version_data['version'];
    } catch (Exception $e) {
        // Handle error here (e.g., display a message or log)
        // You can also set $github_version to a default value
        $github_version = $local_version;
    }
} else {
    // No internet connection, set $github_version to a default value
    $github_version = $local_version;
}
// Fonction pour vérifier la connexion Internet
function checkInternetConnection() {
  $connected = @fsockopen("www.google.com", 80);
  return $connected !== false;
}


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Paramètre du CMS</title>
    <!-- Importer le fichier de style Bootstrap -->
    <script src="../components/tailwind.js"></script>
        <style>
        .location-event {
            background-color: #b52424;
            color: white;
            border: none;
        }
        .prestation-event {
            background-color: #3024b5;
            color: white;
            border: none;
        }
        .fc-event:hover {
            background-color: #555; /* Couleur de fond au survol */
            cursor: pointer; /* Curseur au survol (facultatif) */
        }
        .fc-event:hover a {
            color: black;
        }
        .fc-event:hover td {
            color: black;
        }
    </style>
</head>

<body style="background: #fff;">
<?php 
            $basePath = '../'; 
            include('../components/navbar1.php');
            include('../components/navbar2.php');
        ?>


    <div class="mx-auto lg:ml-80">
        <div class="basis-1/4 flex-0 ml-[1rem] mr-[10rem]  p-4">
    <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px">
            <li class="me-2">
                <a id="tab1" href="#tab1" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500">Général</a>
            </li>
            <li class="me-2">
                <a id="tab2" href="#tab2" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" aria-current="page">Base de données</a>
            </li>
            <li class="me-2">
                <a id="tab3" href="#tab3" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" aria-current="page">Mises à jours</a>
            </li>
        </ul>
    </div>
    <div id="tab1Content" class="hidden">
    <div class="mx-auto my-10 flex flex-wrap flex-row">
    
    <div class="basis-1/4 w-1/4 flex-1 mr-[1rem] border rounded p-4 shadow-md">
      <!-- Conteneur 1 -->
      <h1 class="text-3xl font-bold mb-8 ml-4 mt-5">Paramètres Administrateur du CMS</h1>
      <form class="max-w-md mx-auto" action="updatecms1.php" method="post">
  <div class="relative z-0 w-full mb-5 group">
      <input type="text" name="name" id="name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" value="<?php echo $config_data['name']?>" required />
      <label for="floating_name" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nom de l'entreprise</label>
  </div>
  <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Enregistrer</button>
</form>
    </div>
    <div class="basis-1/2 w-1/2 flex-1 mr-[0rem] border rounded p-4 shadow-md">
      <!-- Conteneur 1 -->
      <h1 class="text-3xl font-bold mb-8 ml-4 mt-5">Les images</h1>
      <form class="max-w-md mx-auto bg-white p-6 rounded-lg" action="updatecms3.php" method="post" enctype="multipart/form-data">

<!-- Logo -->
<div class="mb-6"> 
<label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file</label>
<input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="file_input_help"  id="logo_input" name="logo_input" type="file">
</div>

<!-- Logo actuel avec arrière-plan Tailwind en forme de cercle -->
<div class="mb-6 bg-cover bg-center rounded-full" style="background-image: url('../images/logo.png'); width: 100px; height: 100px;"></div>

<!-- Bannière -->
<div class="mb-6">
    <label class="block text-sm font-medium text-gray-900 dark:text-white" for="banniere_input">BANNIERE</label>
    <input class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="banniere_input" name="banniere_input" type="file">
</div>

<!-- Bannière de connexion actuelle avec arrière-plan Tailwind -->
<div class="mb-6 bg-cover bg-center" style="background-image: url('../images/banniere.png'); width: 100%; height: 200px;"></div>

<button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-white">Enregistrer</button>
</form>



    </div>

  </div>
  </div>
    </div>

    <div id="tab2Content" class="hidden">
    <div class="mx-auto my-10 flex flex-wrap flex-row">
    
    <div class="basis-full w-full flex-1 mr-[1rem] border rounded p-4 shadow-md">
    <div class="flex items-center p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
  <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
  </svg>
  <span class="sr-only">ATTENTION</span>
  <div>
    <span class="font-medium">Si le site fonctionn très bien,</span> vous n'etes pas obligez de changer des infos ici, sauf si vous en êtes sur sinon a la moindre erreur vous pouvez modifier le fichier config.json pour les informations de la db.
  </div>

</div>
<?php if ($db->connect_error) {
    die("Connection échoué: " . $db->connect_error);
} else {
  echo('<div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800" role="alert">
  <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
  </svg>
  <span class="sr-only">Info</span>
  <div>
    <span class="font-medium">Base de données connecté
  </div>
</div>');
}
?>
    <h1 class="text-3xl font-bold mb-8 ">Informations de base de donnée</h1>
    <form class="max-w-md mx-auto" action="updatecms2.php" method="post">
  <div class="relative z-0 w-full mb-5 group">
      <input type="text" name="db_name" id="db_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" value="<?php echo $config_data['db_name'];?>" required />
      <label for="floating_email" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nom de la base de donnée</label>
  </div>
  <div class="relative z-0 w-full mb-5 group">
      <input type="text" name="db_host" id="db_host" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" value="<?php echo $config_data['db_host'];?>" required />
      <label for="floating_email" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Adresse de la basse de donnée</label>
  </div>
  <div class="grid md:grid-cols-2 md:gap-6">
    <div class="relative z-0 w-full mb-5 group">
        <input type="text" name="db_username" id="db_username" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" value="<?php echo $config_data['db_username'];?>" required />
        <label for="floating_first_name" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Identifiant</label>
    </div>
    <div class="relative z-0 w-full mb-5 group">
        <input type="password" name="db_password" id="db_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" value="<?php echo $config_data['db_password'];?>" />
        <label for="floating_last_name" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Mot de passe (optionnel)</label>
    </div>
  </div>
  <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Enregistrer</button>

</form>

    </div>

</div>
 

</div>
<div id="tab3Content" class="hidden">
    <div class="mx-auto my-10 flex flex-wrap flex-row">
        <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/3 xl:w-1/4 flex-1 mx-2 mb-4">
            <?php
            $updateAvailable = (float)$github_version > (float)$local_version && !isset($_GET['update']);
            ?>
            <div class="bg-white p-6 rounded-md shadow-md">
            <h1 class="text-3xl font-bold mb-8 ml-4 mt-5">Mises a jour du cms</h1>
                <p class="mb-4">
                    Version actuelle: <span class="font-bold"><?= $local_version ?></span>
                </p>
                <p class="mb-4">
                    Version GitHub: <span class="font-bold"><?= $github_version ?></span>
                </p>
                <?php if ($updateAvailable): ?>
                  <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
  <span class="font-medium">Une mise à jour est disponnible!</span> Vous pouvez mettre à jour dès maintenant
</div>                    <form id="updateForm" action="update.php" method="post">
                        <a id="updateButton" href="updatecms.php" class="bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 text-white font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-block">
                            Mettre à jour
                        </a>
                    </form>
                <?php else: ?>
                    <p class="text-gray-600 mb-4">Vous utilisez la version la plus récente.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
                </div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var calendarEl = document.getElementById("calendrier");

        var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "listDay", // Vue quotidienne
    initialDate: "<?php echo $date_string, "T", $time_string;?>", // Date d'aujourd'hui
    nowIndicator: true, // Activer l'indicateur "now"
    headerToolbar: {
        left: "",
        center: "title",
        right: "listDay,dayGridMonth",
    },
    events: <?php echo json_encode($events); ?> ,
    locale: 'fr',
    eventRender: function (info) {
        // Personnaliser l'apparence en fonction de la classe CSS
        var className = info.event.extendedProps.className;
        info.el.classList.add(className);
    }
});

calendar.render();

    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.0.0/flowbite.min.js"></script>

<script>
// Votre script JavaScript ici
const tabs = ['tab1', 'tab2', 'tab3'];
const tabContents = ['tab1Content', 'tab2Content', 'tab3Content'];
const activeClass = 'inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500';
const inactiveClass = 'inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300';

tabs.forEach((tab, index) => {
    document.getElementById(tab).addEventListener('click', () => {
        showTabContent(index);
        updateTabClasses(index);
    });
});

function showTabContent(index) {
    tabContents.forEach((id, tabIndex) => {
        const tabContent = document.getElementById(id);
        if (tabIndex === index) {
            tabContent.classList.remove('hidden');
        } else {
            tabContent.classList.add('hidden');
        }
    });
}

function updateTabClasses(selectedIndex) {
    tabs.forEach((tab, index) => {
        const tabElement = document.getElementById(tab);
        if (index === selectedIndex) {
            tabElement.setAttribute('class', activeClass);
        } else {
            tabElement.setAttribute('class', inactiveClass);
        }
    });
}

showTabContent(0); // Afficher le premier onglet par défaut
updateTabClasses(0); // Mettre à jour les classes pour le premier onglet par défaut

</script>
</body>

</html>
