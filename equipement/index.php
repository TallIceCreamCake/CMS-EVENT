<?php
session_start();

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
$query = $db->prepare("SELECT * FROM materiel");
$query->execute();
$result = $query->get_result();

if ($result) {
    $equipements = $result->fetch_all(MYSQLI_ASSOC);
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

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Equipement</title>
    <!-- Importer le fichier de style Bootstrap -->
    <script src="https://cdn.tailwindcss.com"></script>
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
    <div data-dial-init class="fixed end-6 bottom-6 group z-10">
    <form action="new.php">
    <button type="submit" data-dial-toggle="speed-dial-menu-square" aria-controls="speed-dial-menu-square" aria-expanded="false" class="flex items-center justify-center text-white bg-blue-700 rounded-lg w-14 h-14 hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800">
        <svg class="w-5 h-5 transition-transform group-hover:rotate-45" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
        </svg>
    </button>
    </form>
</div>

<div class="container" id="content">
    <h1 class="text-2xl font-bold ml-[5rem] mt-[2rem] mb-[2rem] ml-[5rem] mr-[3rem]">Equipement</h1>
    <div class="grid md:grid-cols-2 md:gap-6 ml-[5rem] mr-[3rem]">

        <div class="flex items-center mb-3">
            <div class="relative z-0 w-full mb-5 group mr-[1rem]"><input class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" type="text" id="searchPrestationInput" placeholder="Nom de prestation"></div>
            <!-- Barre de recherche par nom de client -->
        </div>

        <div class="table-responsive">
            <table class="table" id="customers">
                <!-- ... le reste de votre code ... -->
            </table>
        </div>
    </div>
</div>

<div class="ml-[5rem] mr-[3rem] relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="customers">
                <thead class="text-l text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                    <th scope="col" class="w-50 px-6 py-3">N°</th>
                    <th scope="col" class="px-6 py-3">NOM</th>
                    <th scope="col" class="px-6 py-3">TYPE</th>
                    <th scope="col" class="px-6 py-3">SOUS-TYPE</th>
                    <th scope="col" class="px-6 py-3">ACTION</th>
                    </tr>
                </thead>
                <tbody class="">
    <?php foreach ($equipements as $equipement) : ?>
        <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
            <td scope="row" class="text-l px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $equipement['id']; ?></td>
            <td class="text-l px-6 py-4"><?php echo $equipement['nom']; ?></td>
            <td class="text-l px-6 py-4"><?php echo $equipement['type']; ?></td>
            <td class="text-l px-6 py-4"><?php echo $equipement['soustype']; ?></td>

            <td class="text-l px-6 py-4">
                <form action="details.php" method="get">
                    <input type="hidden" name="materiel_id" value="<?php echo $equipement['id']; ?>">
                    <button class="" type="submit"><a class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Voir les détails</a></button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

            </table>
        </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script>
            const searchPrestationInput = document.getElementById("searchPrestationInput");

        function searchByPrestation() {
    const searchPrestationTerm = searchPrestationInput.value.toLowerCase();
    const rows = document.querySelectorAll("#customers tbody tr");

    rows.forEach(row => {
        const nomCell = row.querySelector("td:nth-child(2)");
        if (nomCell) {
            const nom = nomCell.textContent.toLowerCase();
            // Affichez toutes les lignes si le champ de recherche est vide
            if (searchPrestationTerm === '' || nom.includes(searchPrestationTerm)) {
                row.style.display = "table-row";
            } else {
                row.style.display = "none";
            }
        }
    });
}
searchPrestationInput.addEventListener("input", searchByPrestation);

</script>

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

</body>

</html>
