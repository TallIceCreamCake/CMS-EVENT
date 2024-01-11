<?php
session_start();
if(isset($_GET['deconnexion']))
{ 
    if($_GET['deconnexion'] == true)
    { 
        session_unset();
        header("location:login.php");
    }
}
if (empty($_SESSION['username'])) {
    header("Location: login.php");
    exit(); 
}
$config_file = __DIR__ . '/config.json';
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
  header("Location: ./installation");
}
$sql = "SELECT * FROM utilisateur";
$result = $db->query($sql);
$users = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users = array(
            'id' => $row['id'],
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'mail' => $row['mail'],
        );
        $users[] = $users;
    }
}


// Local version
$local_version = $config_data['version'];

$github_version_url = 'https://raw.githubusercontent.com/TallIceCreamCake/CMS-EVENT/main/config.json';

// Check if there is an internet connection
if (checkInternetConnection()) {
    // Get GitHub version
    try {
        $github_version_data = json_decode(file_get_contents($github_version_url), true);
        $github_version = $github_version_data['version'];
    } catch (Exception $e) {
        // Gestion de l'erreur ici (par exemple, affichage d'un message ou journalisation)
        // Vous pouvez également définir $github_version sur une valeur par défaut
        $github_version = $local_version;
    }
} else {
    // Pas de connexion Internet, définissez $github_version sur une valeur par défaut
    $github_version = $local_version;
}

// Check if an update is available

// Construct the link with update parameter
if ((float)$github_version > (float)$local_version && !isset($_GET['update'])) {
    header('Location: index.php?update=1');
    exit();
}

// Fonction pour vérifier la connexion Internet
function checkInternetConnection() {
    $connected = @fsockopen("www.google.com", 80);
    return $connected !== false;
}

  
$querymatos = $db->prepare("SELECT * FROM materiel");
$querymatos->execute();
$resultmatos = $querymatos->get_result();

if ($resultmatos) {
  $equipementsdb = $resultmatos->fetch_all(MYSQLI_ASSOC);
}



$sql = "SELECT nom, date_debut, date_fin, type FROM reservations";
$result = $db->query($sql);
$events = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $event = array(
            'title' => $row['nom'],
            'start' => $row['date_debut'],
            'end' => $row['date_fin'],
            'className' => ($row['type'] == 'location') ? 'location-event' : 'prestation-event',
        );
        $events[] = $event;
    }
}
$datetime = new DateTime('now', new DateTimeZone('Europe/Paris'));
$date_string = $datetime->format('Y-m-d');
$time_string = $datetime->format('H:i:s');
$username = $_SESSION['username'];
$query = $db->prepare("SELECT role FROM utilisateur WHERE nom_utilisateur = ?");
$query->bind_param("s", $username);
$query->execute();
$query->bind_result($role);
$query->fetch();
$showAdminLink = ($role === 'admin');


?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Index</title>
    <script src="./components/tailwind.js"></script>
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
        background-color: #555;
        cursor: pointer;
    }

    .fc-event:hover a {
        color: black;
    }

    .fc-event:hover td {
        color: black;
    }
    </style>
</head>

<body style="background: #fff;" >
        <?php 
            $basePath = './'; 
            include('./components/navbar1.php');
            include('./components/navbar2.php');
        ?>
        <div class="mx-auto lg:ml-80">
            <?php


if (isset($_GET['update'])) {
  $upp = $_GET['update'];
  if ($upp == 1 || $upp == 2) {
    echo '<div id="alert-additional-content-1" class="rounded border-s-4 border-blue-500 bg-blue-50 p-4  ml-[10rem] mr-[10rem] mt-[2rem]" role="alert">
    <div class="flex items-center">
      <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
      </svg>
      <span class="sr-only">Info</span>
      <h3 class="text-lg font-medium">Info mise a jour</h3>
    </div>
    <div class="mt-2 mb-4 text-sm">
        Une nouvelle mise a jour est disponnible, version actuel : '. $local_version .' nouvelle version ' . $github_version . ', il vous faut les droits admin pour mettre à jour depuis les paramètres du CMS
    </div>
    </div>';
  }
}

            ?>
            <div class="mx-auto my-10 flex flex-wrap flex-row ">
                <div class="basis-1/4 w-1/4 flex-1 ml-[10rem] mr-[0rem] border rounded p-4 shadow-md">
                    <h1 class="text-3xl font-bold mb-8">Mon agenda</h1>
                    <div class="pb-10">
                        <div id="calendrier"></div>
                    </div>
                </div>
                <div class="basis-1/2 flex-1 ml-[1rem] mr-[10rem] border rounded p-4 shadow-md">
                    <h1 class="text-3xl font-bold">Actions rapide</h1>
   <div class="grid grid-cols-3 gap-4 p-4 lg:grid-cols-4">
      <div class="p-4 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:hover:bg-gray-600 dark:bg-gray-700">
         <a href="./prestation"><div class="flex justify-center items-center p-2 mx-auto mb-2 bg-gray-200 dark:bg-gray-600 rounded-full w-[48px] h-[48px] max-w-[48px] max-h-[48px]">
            <svg class="inline w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
               <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
               <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
            </svg>
         </div>
         <div class="font-medium text-center text-gray-500 dark:text-gray-400">Prestation</div></a>
      </div>
      <div class="p-4 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:hover:bg-gray-600 dark:bg-gray-700">
      <a href="./clients"><div class="flex justify-center items-center p-2 mx-auto mb-2 bg-gray-200 dark:bg-gray-600 rounded-full w-[48px] h-[48px] max-w-[48px] max-h-[48px]">
            <svg class="inline w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
               <path d="M18 0H2a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2ZM9 6v2H2V6h7Zm2 0h7v2h-7V6Zm-9 4h7v2H2v-2Zm9 2v-2h7v2h-7Z"/>
            </svg>
         </div>
         <div class="font-medium text-center text-gray-500 dark:text-gray-400">Client</div></a>
      </div>
      <div class="p-4 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:hover:bg-gray-600 dark:bg-gray-700">
      <a href="./equipement"><div class="flex justify-center items-center p-2 mx-auto mb-2 bg-gray-200 dark:bg-gray-600 rounded-full w-[48px] h-[48px] max-w-[48px] max-h-[48px]">
            <svg class="inline w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
               <path d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z"/>
            </svg>
         </div>
         <div class="font-medium text-center text-gray-500 dark:text-gray-400">Equipement</div></a>
      </div>
      <div class="p-4 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:hover:bg-gray-600 dark:bg-gray-700">
      <a href="#"><div class="flex justify-center items-center p-2 mx-auto mb-2 bg-gray-200 dark:bg-gray-600 rounded-full w-[48px] h-[48px] max-w-[48px] max-h-[48px]">
            <svg class="inline w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
               <path d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z"/>
            </svg>
         </div>
         <div class="font-medium text-center text-gray-500 dark:text-gray-400">Agenda</div></a>
      </div>
      <div class="p-4 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:hover:bg-gray-600 dark:bg-gray-700">
      <a href="./entrepot"><div class="flex justify-center items-center p-2 mx-auto mb-2 bg-gray-200 dark:bg-gray-600 rounded-full w-[48px] h-[48px] max-w-[48px] max-h-[48px]">
            <svg class="inline w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
               <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 2a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1M2 5h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm8 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/>
            </svg>
         </div>
         <div class="font-medium text-center text-gray-500 dark:text-gray-400">Entrepot</div></a>
      </div>
                </div>
            </div>
        </div>
        <div class="basis-full w-full max-w-screen-xl ml-[10rem] mr-[20rem] border rounded p-4 shadow-md">
                    <h1 class="text-3xl font-bold mb-8">Vérifier rapidement le stock</h1>
                    <div class="pb-10">
    <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Rechercher</label>
    <div class="relative">
        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
            </svg>
        </div>
        <input type="search" id="searchMatoseInput" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Rechercher un équipement..." required>

        <div id="floating_helper_text" class="absolute w-full z-10 left-0 p-4 bg-white border border-gray-300 text-xs text-gray-500 dark:text-gray-400" style="display: none;">
            <div id="noResultsMessage" class="text-l px-6 py-2" style="display: none;">Aucun résultat trouvé.</div>

            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mt-4" id="customers">
                <tbody>
                    <?php foreach ($equipementsdb as $equipement) : ?>
                        <tr class="bg-white">
                            <td class="text-l px-6 py-2">
                                <p class="text-base"><?php echo $equipement['nom']; ?></p>
                            </td>
                            <td class="text-l px-6 py-2">
                            <span class="flex items-center text-sm font-medium text-gray-900 dark:text-white me-3"><span class="flex w-2.5 h-2.5 bg-green-600 rounded-full me-1.5 flex-shrink-0"></span>En Stock</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>

                </div>

    </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

    <script>
document.addEventListener("DOMContentLoaded", function() {
    const searchMatoseInput = document.getElementById("searchMatoseInput");
    const noResultsMessage = document.getElementById("noResultsMessage");
    const floatingHelperText = document.getElementById("floating_helper_text");

    function searchByMatos() {
        const searchWarehouseTerm = searchMatoseInput.value.trim().toLowerCase();
        const rows = document.querySelectorAll("#customers tbody tr");
        let resultsFound = false;

        if (searchWarehouseTerm === '') {
            floatingHelperText.style.display = "none";
        } else {
            floatingHelperText.style.display = "block";
        }

        rows.forEach(row => {
            const nomCell = row.querySelector("td");
            if (nomCell) {
                const nom = nomCell.textContent.trim().toLowerCase();
                // Affichez toutes les lignes si le champ de recherche est vide
                if (searchWarehouseTerm === '' || nom.includes(searchWarehouseTerm)) {
                    row.style.display = "table-row";
                    resultsFound = true;
                } else {
                    row.style.display = "none";
                }
            }
        });

        // Afficher le message s'il n'y a aucun résultat
        noResultsMessage.style.display = resultsFound ? "none" : "block";
    }

    searchMatoseInput.addEventListener("input", searchByMatos);

    // Appeler la fonction pour la première fois
    searchByMatos();
});


</script>




    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var calendarEl = document.getElementById("calendrier");
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: "listDay",
            initialDate: "<?php echo $date_string, "T", $time_string;?>",
            nowIndicator: true,
            headerToolbar: {
                left: "",
                center: "title",
                right: "listDay,dayGridMonth",
            },
            events: <?php echo json_encode($events); ?>,
            locale: 'fr',
            eventRender: function(info) {
                var className = info.event.extendedProps.className;
                info.el.classList.add(className);
            }
        });
        calendar.render();
    });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.0.0/flowbite.min.js"></script>
</body>

</html>