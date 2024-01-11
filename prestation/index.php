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
$query = $db->prepare("SELECT * FROM reservations");
$query->execute();
$result = $query->get_result();

if ($result) {
    $reservations = $result->fetch_all(MYSQLI_ASSOC);
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

<!-- Le reste de votre code HTML reste inchangé -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des prestations</title>
    <!-- Importer le fichier de style Bootstrap -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body style='background:#fff;'>
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
    <h1 class="text-2xl font-bold ml-[5rem] mt-[2rem] mb-[2rem] ml-[5rem] mr-[3rem]">Prestations</h1>
    
    <div class="grid md:grid-cols-2 md:gap-6 ml-[5rem] mr-[3rem]">

        <div class="flex items-center mb-3">
            <div class="relative z-0 w-full mb-5 group mr-[1rem]"><input class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" type="text" id="searchPrestationInput" placeholder="Nom de prestation"></div>
            <!-- Barre de recherche par nom de client -->
            <div class="relative z-0 w-full mb-5 group"><input class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" type="text" id="searchClientInput" placeholder="Nom de client"></div>
        </div>
    </div>
</div>

<div class="ml-[5rem] mr-[3rem] relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="customers">
                <thead class="text-l text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                    <th scope="col" class="w-50 px-6 py-3"></th>
                        <th scope="col" class="px-6 py-3">N°</th>
                        <th scope="col" class="px-6 py-3">NOM</th>
                        <th scope="col" class="px-6 py-3">STATUT</th>
                        <th scope="col" class="px-6 py-3">CLIENT</th>
                        <th scope="col" class="px-6 py-3">TYPE</th>
                        <th scope="col" class="px-6 py-3">DATE</th>
                        <th scope="col" class="px-6 py-3">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="">
    <?php foreach ($reservations as $reservation) : ?>
      <?php 
        $clientid = $reservation['clients'];

        // Maintenant, récupérez les noms du matériel correspondants en utilisant les IDs
    
        if (isset($clientid)) {
                $client_query = $db->prepare("SELECT * FROM clients WHERE id = ?");
                $client_query->bind_param("i", $clientid);
                $client_query->execute();
                $client_result = $client_query->get_result();
                $clientinfos = $client_result->fetch_assoc();
            
        }?>
        <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
            <td class="w-50 text-l px-6 py-4 bg-[<?php echo $reservation['color']; ?>]"></td>
            <td scope="row" class="text-l px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $reservation['id']; ?></td>
            <td class="text-l px-6 py-4"><?php echo $reservation['nom']; ?></td>
            <td class="text-l px-6 py-4">
                <div class="flex items-center space-x-2">
                <?php
$couleur1 = $reservation['step1'] == 1 ? '#ff9900' : ($reservation['step1'] == 2 ? '#00d438' : '#333');
$couleur2 = $reservation['step2'] == 1 ? '#ff9900' : ($reservation['step2'] == 2 ? '#00d438' : '#333');
$couleur3 = $reservation['step3'] == 1 ? '#ff9900' : ($reservation['step3'] == 2 ? '#00d438' : '#333');
$couleur4 = $reservation['step4'] == 1 ? '#ff9900' : ($reservation['step4'] == 2 ? '#00d438' : '#333');
?>


                    <svg class="imgsvg1" width="20px" height="20px" xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512">
                        <path path d="M24,10.5v8c0,3.03-2.47,5.5-5.5,5.5H5.5c-3.03,0-5.5-2.47-5.5-5.5V8.5C0,5.47,2.47,3,5.5,3H13.5c.83,0,1.5,.67,1.5,1.5s-.67,1.5-1.5,1.5H5.5c-.96,0-1.79,.54-2.21,1.33l6.94,6.94c.95,.95,2.59,.95,3.54,0,.02-.02,2.75-2.4,2.75-2.4,.62-.54,1.57-.48,2.12,.15,.54,.62,.48,1.57-.15,2.12l-2.64,2.3c-1.03,1.01-2.4,1.57-3.85,1.57s-2.85-.57-3.89-1.61L3,11.28v7.22c0,1.38,1.12,2.5,2.5,2.5h13c1.38,0,2.5-1.12,2.5-2.5V10.5c0-.83,.67-1.5,1.5-1.5s1.5,.67,1.5,1.5Zm-3.5-3.5c1.93,0,3.5-1.57,3.5-3.5s-1.57-3.5-3.5-3.5-3.5,1.57-3.5,3.5,1.57,3.5,3.5,3.5Z" fill="<?php echo $couleur1; ?>"></path>
                    </svg>

                    <svg class="imgsvg2" width="20px" height="20px" xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512">
                        <path d="M6.5,22h-2c-2.481,0-4.5-2.019-4.5-4.5v-2c0-.829,.672-1.5,1.5-1.5s1.5,.671,1.5,1.5v2c0,.827,.673,1.5,1.5,1.5h2c.828,0,1.5,.671,1.5,1.5s-.672,1.5-1.5,1.5Zm17.5-4.5v-2c0-.829-.672-1.5-1.5-1.5s-1.5,.671-1.5,1.5v2c0,.827-.673,1.5-1.5,1.5h-2c-.828,0-1.5,.671-1.5,1.5s.672,1.5,1.5,1.5h2c2.481,0,4.5-2.019,4.5-4.5Zm0-9v-2c0-2.481-2.019-4.5-4.5-4.5h-2c-.828,0-1.5,.671-1.5,1.5s.672,1.5,1.5,1.5h2c.827,0,1.5,.673,1.5,1.5v2c0,.829,.672,1.5,1.5,1.5s1.5-.671,1.5-1.5Zm-21,0v-2c0-.827,.673-1.5,1.5-1.5h2c.828,0,1.5-.671,1.5-1.5s-.672-1.5-1.5-1.5h-2C2.019,2,0,4.019,0,6.5v2c0,.829,.672,1.5,1.5,1.5s1.5-.671,1.5-1.5Zm6,6.5v-6c0-1.104-.896-2-2-2s-2,.896-2,2v6c0,1.104,.896,2,2,2s2-.896,2-2ZM14.5,7h0c-.828,0-1.5,.672-1.5,1.5v7c0,.828,.672,1.5,1.5,1.5h0c.828,0,1.5-.672,1.5-1.5v-7c0-.828-.672-1.5-1.5-1.5Zm3.5,0h0c-.552,0-1,.448-1,1v8c0,.552,.448,1,1,1h0c.552,0,1-.448,1-1V8c0-.552-.448-1-1-1Zm-7,0h0c-.552,0-1,.448-1,1v8c0,.552,.448,1,1,1h0c.552,0,1-.448,1-1V8c0-.552-.448-1-1-1Z" fill="<?php echo $couleur2; ?>"></path>
                    </svg>

                    <svg class="imgsvg3" width="20px" height="20px" xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512">
                        <path d="M12,0C5.383,0,0,5.383,0,12c.603,15.898,23.4,15.894,24,0,0-6.617-5.383-12-12-12Zm0,21c-4.963,0-9-4.037-9-9,.452-11.923,17.549-11.92,18,0,0,4.963-4.037,9-9,9Zm4-6.587c0,1.599-1.052,2.957-2.5,3.418v.669c-.034,1.972-2.966,1.971-3,0v-.579c-.955-.206-1.799-.807-2.299-1.67-.414-.717-.169-1.635,.548-2.05,.716-.414,1.634-.169,2.05,.548,.091,.157,.253,.251,.434,.251h1.181c.629,.018,.809-.917,.218-1.132l-2.376-.95c-3.11-1.155-2.884-5.845,.245-6.749v-.669c.034-1.972,2.967-1.971,3,0v.579c.955,.206,1.799,.807,2.299,1.67,.414,.717,.169,1.635-.548,2.049-.716,.414-1.635,.169-2.05-.547-.091-.157-.253-.251-.434-.251h-1.181c-.629-.018-.809,.917-.218,1.132l2.376,.95c1.37,.548,2.255,1.855,2.255,3.331Z" fill="<?php echo $couleur3; ?>"></path>
                    </svg>

                    <svg class="imgsvg4" width="20px" height="20px" xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512">
                        <path d="M4.5,7A3.477,3.477,0,0,1,2.025,5.975L.5,4.62a1.5,1.5,0,0,1,2-2.24L4.084,3.794A.584.584,0,0,0,4.5,4a.5.5,0,0,0,.353-.146L8.466.414a1.5,1.5,0,0,1,2.068,2.172L6.948,6A3.449,3.449,0,0,1,4.5,7ZM24,3.5A1.5,1.5,0,0,0,22.5,2h-8a1.5,1.5,0,0,0,0,3h8A1.5,1.5,0,0,0,24,3.5ZM6.948,14l3.586-3.414A1.5,1.5,0,0,0,8.466,8.414l-3.613,3.44a.5.5,0,0,1-.707,0L2.561,10.268A1.5,1.5,0,0,0,.439,12.39l1.586,1.585A3.5,3.5,0,0,0,6.948,14ZM24,11.5A1.5,1.5,0,0,0,22.5,10h-8a1.5,1.5,0,0,0,0,3h8A1.5,1.5,0,0,0,24,11.5ZM6.948,22l3.586-3.414a1.5,1.5,0,0,0-2.068-2.172l-3.613,3.44A.5.5,0,0,1,4.5,20a.584.584,0,0,1-.416-.206L2.5,18.38a1.5,1.5,0,0,0-2,2.24l1.523,1.355A3.5,3.5,0,0,0,6.948,22ZM24,19.5A1.5,1.5,0,0,0,22.5,18h-8a1.5,1.5,0,0,0,0,3h8A1.5,1.5,0,0,0,24,19.5Z" fill="<?php echo $couleur4; ?>"></path>
                    </svg>
                </div>
            </td>

            <td class="text-l px-6 py-4"><?php echo $clientinfos['nom'];?> <?php echo $clientinfos['prenom'];?></td>
            <td class="text-l px-6 py-4"><?php echo $reservation['type']; ?></td>
            <td class="text-l px-6 py-4"><?php echo $reservation['date_debut']; ?></td>
            <td class="text-l px-6 py-4">
                <form action="details.php" method="get">
                    <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                    <button class="" type="submit"><a class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Voir les détails</a></button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

            </table>
        </div>
                    </div>
    <!-- ... Votre code JavaScript déplacé ici ... -->
    <script>
        // Récupérez les éléments de recherche par ID
        const searchPrestationInput = document.getElementById("searchPrestationInput");
        const searchClientInput = document.getElementById("searchClientInput");

        function searchByPrestation() {
    const searchPrestationTerm = searchPrestationInput.value.toLowerCase();
    const rows = document.querySelectorAll("#customers tbody tr");

    rows.forEach(row => {
        const nomCell = row.querySelector("td:nth-child(3)");
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

        // Créez des fonctions pour gérer la recherche par nom de client
        function searchByClient() {
            const searchClientTerm = searchClientInput.value.toLowerCase();
            const rows = document.querySelectorAll("#customers tbody tr");

            rows.forEach(row => {
                const clientCell = row.querySelector("td:nth-child(5)");
                if (clientCell) {
                    const client = clientCell.textContent.toLowerCase();
                    if (client.includes(searchClientTerm)) {
                        row.style.display = "table-row";
                    } else {
                        row.style.display = "none";
                    }
                }
            });
        }

        // Ajoutez des gestionnaires d'événement "input" pour les deux barres de recherche
        searchPrestationInput.addEventListener("input", searchByPrestation);
        searchClientInput.addEventListener("input", searchByClient);

    </script>

</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.0.0/flowbite.min.js"></script>

</html>
