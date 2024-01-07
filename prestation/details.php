<?php
session_start();
if (empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['reservation_id'])) {
    $reservation_id = $_GET['reservation_id'];

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
    // Récupérez les détails de la réservation en fonction de $reservation_id
    $query = $db->prepare("SELECT * FROM reservations WHERE id = ?");
    $query->bind_param("i", $reservation_id);
    $query->execute();
    $result = $query->get_result();
    $reservation_details = $result->fetch_assoc();

    // Si vous avez stocké les IDs de matériel sous forme de chaîne séparée par des virgules, vous pouvez les diviser en un tableau.
    $materiel_ids = explode(',', $reservation_details['materiel']);

    // Maintenant, récupérez les noms du matériel correspondants en utilisant les IDs
    $material_names = array();

    if (isset($materiel_ids)) {
        foreach ($materiel_ids as $material_id) {
            $material_query = $db->prepare("SELECT nom FROM materiel WHERE id = ?");
            $material_query->bind_param("i", $material_id);
            $material_query->execute();
            $material_result = $material_query->get_result();
            $material = $material_result->fetch_assoc();
        }
    }
} else {
    // Redirigez l'utilisateur vers une page d'erreur si l'identifiant de la réservation est manquant.
    header("Location: erreur.php"); // Remplacez "erreur.php" par la page d'erreur souhaitée.
    exit();
}

$prixaprestva = $reservation_details['prix'] + ($reservation_details['prix'] * (20/100));
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
    <title>Locations</title>
    <!-- Importer le fichier de style Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="../jsCalendar.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.0.0/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v3.0.0/mapbox-gl.js"></script>

    <style>
                .hidden {
            display: none;
        }
        .map-container {
    position: relative;
    width: 100%;
    padding-bottom: 75%;
    height: 0;
 }

 .map-container #map {
    position: absolute;
    top: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
 }    </style>
</head>
<body style='background:#fff;'>
<?php 
            $basePath = '../'; 
            include('../components/navbar1.php');
            include('../components/navbar2.php');
        ?>
    
    <div class="mx-auto lg:ml-80">

    <div class="basis-1/4 flex-0 ml-[1rem] mr-[10rem]  p-4">
    <h1 class="text-2xl font-bold mt-[2rem] mb-[1rem]">Détail de la prestation n°<?php echo $reservation_details['id']; ?></h1>
    </div>
        <div class="ml-[5rem]">
        <?php if (isset($reservation_details)) : ?>
<div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Etes vous sur de vouloir supprimer cette prestation ?</h3>
                            <form action="delete.php" method="post">
                                <input type="hidden" name="reservation_id" value="<?php echo $reservation_details['id']; ?>">
                                <button data-modal-hide="popup-modal" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">Oui</button>
                            </form>
            </div>
        </div>
    </div>
</div>

<div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px">
            <li class="me-2">
                <a id="tab1" href="#tab1" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500">Général</a>
            </li>
            <li class="me-2">
                <a id="tab2" href="#tab2" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" aria-current="page">Equipement</a>
            </li>
            <li class="me-2">
                <a id="tab3" href="#tab3" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Financié</a>
            </li>
            <li class="me-2">
                <a id="tab4" href="#tab4" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Contacts & lieu</a>
            </li>
        </ul>
    </div>

    <div id="tab1Content" class="hidden">
    <div class="mx-auto my-10 flex flex-wrap flex-row">
    
    <div class="basis-1/3 w-1/3 flex-1 mr-[0rem] border rounded p-4 shadow-md">
      <!-- Conteneur 1 -->
      <h1 class="text-3xl font-bold mb-8 ">Informations général</h1>
      
      <form class="" action="editprocess.php" method="post">
            <input type="hidden" name="reservation_id" value="<?php echo $reservation_details['id']; ?>>">

            <div class="relative z-0 w-full mb-5 group">
                <input type="text" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" id="nom" name="nom" value="<?php echo $reservation_details['nom']; ?>" required>
                <label for="nom" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nom :</label>
            </div>   
            <div class="relative z-0 w-full mb-5 group">
            
            <input class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" type="color" id="color" name="color" value="<?php echo $reservation_details['color']; ?>" />
            <label for="nom" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Couleur atribué :</label> <br>
</div>
            <div class="relative z-0 w-full mb-5 group">

                <textarea class="block mb-2 py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" id="description" name="description"><?php echo $reservation_details['description']; ?></textarea>
                <label for="description" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Description :</label>
            </div>
            <div class="grid md:grid-cols-2 md:gap-6">
            <div class="relative z-0 w-full mb-5 group">
                <input type="date" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" id="date_debut" name="date_debut" value="<?php echo $reservation_details['date_debut']; ?>" required>
                <label for="date_debut" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Date de début :</label>

            </div>
            <div class="relative z-0 w-full mb-5 group">
                <input type="date" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" id="date_fin" name="date_fin" value="<?php echo $reservation_details['date_fin']; ?>" required>
                <label for="date_fin" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Date de fin :</label>

            </div>
        </div>
        <div class="form-group">
                <label for="materiel">Matériel :</label>
                
                <button data-modal-target="default-modal" data-modal-toggle="default-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                Sélectionner du matériel
</button><br>
            </div>

            <div id="default-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                Sélectionner du matériel
                </h3> 
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
            <div class="form-group">
                    <label for="filterType">Filtrer par Type :</label>
                    <select class="form-control" id="filterType" name="type" onchange="updateSubcategory()">
                        <option value="all">Toutes les catégories</option>
                        <option value="son">Son</option>
                        <option value="lumiere">Lumière</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="searchMaterial">Rechercher du matériel :</label>
                    <input type="text" class="form-control" id="searchMaterial">
                </div>

                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" id="materialTable">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3" scope="col">Matériel</th>
                            <th class="px-6 py-3" scope="col">Type</th>
                            <th class="px-6 py-3" scope="col">Sous-catégorie</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
    $config_file = __DIR__ . '/../config.json';
    $config_data = json_decode(file_get_contents($config_file), true);
    $db_host = $config_data['db_host'];
    $db_username = $config_data['db_username'];
    $db_password = $config_data['db_password'];
    $db_name = $config_data['db_name'];
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

                        // Requête pour récupérer le matériel depuis la table "materiel"
                        $sql = "SELECT id, nom, type, soustype FROM materiel";
                        $result = $conn->query($sql);
                        
                        $materialIds = explode(',', $reservation_details['materiel']);

                        // Assurez-vous que $materialIds est un tableau non vide
                        if (!empty($materialIds)) {
                            // Utilisez JavaScript pour cocher les cases correspondantes dans le modal
                            echo '<script>';
                            echo 'document.addEventListener("DOMContentLoaded", function () {';
                            foreach ($materialIds as $materialId) {
                                echo 'document.querySelector(\'input[type="checkbox"][value="' . $materialId . '"]\').checked = true;';
                            }
                            echo '});';
                            echo '</script>';
                        }
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $id = $row['id'];
                                $nom = $row['nom'];
                                $type = $row['type'];
                                $soustype = $row['soustype'];
                                echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">';
                                echo '<td><input type="checkbox" name="material[]" value="' . $id . '"> ' . $nom . '</td>';
                                echo '<td>' . $type . '</td>';
                                echo '<td>' . $soustype . '</td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>


            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button data-modal-hide="default-modal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Enregistrer</button>
            </div>
        </div>
    </div>
</div>
            <div class="relative z-0 w-full mb-5 group">
                <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" id="type" name="type">
                    <option value="location" <?php echo ($reservation_details['type'] === 'location') ? 'selected' : ''; ?>>Location</option>
                    <option value="prestation" <?php echo ($reservation_details['type'] === 'prestation') ? 'selected' : ''; ?>>Prestation</option>
                </select>
                <label for="type" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Type :</label>
            </div>

            <div class="relative z-0 w-full mb-5 group">
                <input type="text" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" id="client" name="client" list="suggestions" value="<?php echo $reservation_details['clients']; ?>">
                <label for="client" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Client :</label>

                <datalist id="suggestions">
                    <!-- Liste des clients (identique à la page de création) -->
                </datalist>
            </div>

            <div class="relative z-0 w-full mb-5 group">
                <input type="number" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" id="prix" name="prix" value="<?php echo $reservation_details['prix']; ?>">
                <label for="prix" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Prix :</label>
            </div>

            <button type="submit" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Enregistrer les modifications</button>
        </form>
    </div>
    <div class="basis-1/10 w-1/3 flex-1 mr-[0rem]   p-4 ">
    

    <div class="max-w-sm border border-gray-200 rounded-lg shadow dark:border-gray-700">
    <div class="rounded-t-lg w-50 pr-1 py-4 shadow " style="background-color: <?php echo $reservation_details['color']; ?>">
        <!-- Vous pouvez ajouter du contenu supplémentaire ici si nécessaire -->
    </div>
    <div class="p-5">
        <a>
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><?php echo $reservation_details['nom']; ?></h5>
        </a>
        



        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"><?php echo $reservation_details['description']; ?></p>

        <div class="inline-flex rounded-md shadow-sm" role="group">
  <button data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-red-500 rounded-s-lg hover:bg-gray-100 hover:text-red-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
    Supprimer
  </button>
  <form action="./index.php">
  <button  type="submit" class="rounded-r-lg px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
    Fermer
  </button>
  </form>
</div>
    </div>
</div>


  </div>
  </div>
    </div>

    <div id="tab2Content" class="hidden">
    <div class="mx-auto my-10 flex flex-wrap flex-row">
    
    <div class="basis-1/3 w-1/3 flex-1 mr-[0rem] border rounded p-4 shadow-md">
    <h1 class="text-3xl font-bold mb-8 ">Equipement</h1>
    

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <form>
                <input type="hidden" name="reservation_id" value="<?php echo $reservation_details['id']; ?>>">

                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" id="materialTable">
    <tbody>
    <?php
// Récupérer les détails de la réservation en fonction de $reservation_id
$query = $conn->prepare("SELECT * FROM reservations WHERE id = ?");
$query->bind_param("i", $reservation_id);
$query->execute();
$result = $query->get_result();
$reservation_details = $result->fetch_assoc();

// Si vous avez stocké les IDs de matériel sous forme de chaîne séparée par des virgules, vous pouvez les diviser en un tableau.
$materiel_ids = explode(',', $reservation_details['materiel']);

// Affichez les noms du matériel dans le tableau
echo '<table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" id="materialTable">';
echo '<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">';
echo '<tr>';
echo '<th class="px-6 py-3" scope="col">Matériel</th>';
echo '<th class="px-6 py-3" scope="col">Type</th>';
echo '<th class="px-6 py-3" scope="col">Sous-catégorie</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

foreach ($materiel_ids as $material_id) {
    $material_query = $conn->prepare("SELECT nom, type, soustype FROM materiel WHERE id = ?");
    $material_query->bind_param("i", $material_id);
    $material_query->execute();
    $material_result = $material_query->get_result();
    $material = $material_result->fetch_assoc();

    // Affichez les détails du matériel dans le tableau
    echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">';
    echo '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">' . $material['nom'] . '</td>';
    echo '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">' . $material['type'] . '</td>';
    echo '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">' . $material['soustype'] . '</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

$conn->close();
?>

   </tbody>
</table>



            </div>
</form>
        </div>
        <div class="basis-1/10 w-1/3 flex-1 mr-[0rem]   p-4 ">
    

    <div class="max-w-sm border border-gray-200 rounded-lg shadow dark:border-gray-700">
    <div class="rounded-t-lg w-50 pr-1 py-4 shadow " style="background-color: <?php echo $reservation_details['color']; ?>">
        <!-- Vous pouvez ajouter du contenu supplémentaire ici si nécessaire -->
    </div>
    <div class="p-5">
        <a>
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><?php echo $reservation_details['nom']; ?></h5>
        </a>
        


        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"><?php echo $reservation_details['description']; ?></p>

        <div class="inline-flex rounded-md shadow-sm" role="group">
  <button data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-red-500 rounded-s-lg hover:bg-gray-100 hover:text-red-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
    Supprimer
  </button>
  <form action="./index.php">
  <button  type="submit" class="rounded-r-lg px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
    Fermer
  </button>
  </form>

</div>
    </div>
</div>

</div>

        <div>
        </div>
    </div>
      </div>

    <div id="tab3Content" class="hidden">
    <div class="mx-auto my-10 flex flex-wrap flex-row">

    <div class=" mr-[5rem] flex-1 border rounded p-2 shadow-md">
    <h1 class="text-3xl font-bold mb-8 ">Informations Finance</h1>
    

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Catégorie
                </th>
                <th scope="col" class="px-6 py-3">
                    Cout
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Prestation : <?php echo $reservation_details['nom'] ?>, n°<?php echo $reservation_details['id']?>
                </th>
                <td class="px-6 py-4">
                    <?php echo $reservation_details['prix']?> €
                </td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Cout total sans TVA
                </th>
                <td class="px-6 py-4">
                    <?php echo $reservation_details['prix']?> €
                </td>
            </tr>
            <tr class="bg-white dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Cout total avec TVA
                </th>
                <td class="px-6 py-4">
                    <?php echo $prixaprestva?> €
                </td>
            </tr>
        </tbody>
    </table>
</div>
      </div>

      </div>
    </div>

    <div id="tab4Content" class="hidden">
    <div class="mx-auto my-10 flex flex-wrap flex-row">

    <div class="basis-1/10 w-1/10 flex-1 mr-[1rem] border rounded p-2 shadow-md">
      <?php 
        $clientid = $reservation_details['clients'];

        // Maintenant, récupérez les noms du matériel correspondants en utilisant les IDs
    
        if (isset($clientid)) {
                $client_query = $db->prepare("SELECT * FROM clients WHERE id = ?");
                $client_query->bind_param("i", $clientid);
                $client_query->execute();
                $client_result = $client_query->get_result();
                $clientinfos = $client_result->fetch_assoc();
            
        }?>
      <!-- Conteneur 1 -->
      <h1 class="text-3xl font-bold mb-8 ">Informations du client</h1>
      <div class="flex justify-end px-4 pt-4">
    </div>
    <div class="flex flex-col items-center pb-10">
    <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="../images/ppclients/<?php echo ($clientinfos['pp'] == '') ? 'basic.png' : $clientinfos['pp'];?>" alt="Bonnie image"/>
        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white"><?php echo $clientinfos['nom'];?> <?php echo $clientinfos['prenom'];?></h5>
        <p class="font-normal text-gray-700 dark:text-gray-400">ENTREPRISE : <?php echo $clientinfos['entreprise'];?></p>
        <p class="font-normal text-gray-700 dark:text-gray-400">Informations de contact :</p>
        <p class="font-normal text-gray-700 dark:text-gray-400">Numéro de téléphone : <?php echo $clientinfos['phone'];?></p>
        <p class="font-normal text-gray-700 dark:text-gray-400">E-Mail : <?php echo $clientinfos['mail'];?></p>
        <div class="flex mt-4 md:mt-6">
            <a href="../clients/details.php?clients_id=<?php echo $clientinfos['id']?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Voir la fiche client</a>
        </div>
    </div>
    </div>
    <div class="basis-1/10 w-1/10 flex-1 mr-[5rem]  border rounded p-4 shadow-md ">
    <h1 class="text-3xl font-bold mb-8 ">Localisation de la prestation</h1>
        <p class="font-normal text-gray-700 dark:text-gray-400">adresse : <?php echo $reservation_details['lieu']; ?></p><br>
    <div class="map-container">
    <div id="map"></div>
 </div>

<script src="https://unpkg.com/@mapbox/mapbox-sdk/umd/mapbox-sdk.min.js"></script>

<script>
	mapboxgl.accessToken = 'pk.eyJ1IjoiaWNlY3JlYW0xMjMiLCJhIjoiY2tsdzdqNnN6MTA2ZjJ1cDkwbDhzZGRhYiJ9.Ocy6jw3-cPbjVHNxA6CGng';
    const mapboxClient = mapboxSdk({ accessToken: mapboxgl.accessToken });
    mapboxClient.geocoding
        .forwardGeocode({
            query: '<?php echo $reservation_details['lieu']; ?>',
            autocomplete: false,
            limit: 1
        })
        .send()
        .then((response) => {
            if (
                !response ||
                !response.body ||
                !response.body.features ||
                !response.body.features.length
            ) {
                console.error('Invalid response:');
                console.error(response);
                return;
            }
            const feature = response.body.features[0];

            const map = new mapboxgl.Map({
                container: 'map',
                // Choose from Mapbox's core styles, or make your own style with Mapbox Studio
                style: 'mapbox://styles/mapbox/streets-v12',
                center: feature.center,
                zoom: 13
            });

            // Create a marker and add it to the map.
            new mapboxgl.Marker().setLngLat(feature.center).addTo(map);
        });
</script>


      </div>
    </div>
        <?php else : ?>
            <p class="mt-4">La réservation n'a pas été trouvée.</p>
        <?php endif; ?>
        
</div>
</div>
</body>
<script>
    // JavaScript pour filtrer les résultats en fonction du type sélectionné et effectuer une recherche
    document.addEventListener("DOMContentLoaded", function() {
        const filterType = document.getElementById("filterType");
        const searchMaterial = document.getElementById("searchMaterial");
        const materialTable = document.getElementById("materialTable").querySelector("tbody");

        filterType.addEventListener("change", filterResults);
        searchMaterial.addEventListener("input", filterResults);

        function filterResults() {
            const selectedType = filterType.value;
            const searchText = searchMaterial.value.toLowerCase();
            const rows = materialTable.querySelectorAll("tr");

            rows.forEach(function(row) {
                const materialName = row.querySelector("td:first-child").textContent.trim().toLowerCase();
                const materialType = row.querySelector("td:nth-child(2)").textContent.trim();
                const materialSubType = row.querySelector("td:nth-child(3)").textContent.trim();

                if (
                    (selectedType === "all" || materialType === selectedType) &&
                    materialName.includes(searchText)
                ) {
                    row.style.display = "table-row";
                } else {
                    row.style.display = "none";
                }
            });
        }
    });

    function updateSubcategory() {
        const typeSelect = document.getElementById("filterType");
        const subCategory1 = document.getElementById("subCategory1");
        const subCategory2 = document.getElementById("subCategory2");

        if (typeSelect.value === "son") {
            subCategory1.style.display = "block";
            subCategory2.style.display = "none";
        } else if (typeSelect.value === "lumiere") {
            subCategory1.style.display = "none";
            subCategory2.style.display = "block";
        } else {
            subCategory1.style.display = "none";
            subCategory2.style.display = "none";
        }
    }

    updateSubcategory(); // Appeler la fonction pour afficher la sous-catégorie au chargement de la page.


    
</script>
<script>
    const tabs = ['tab1', 'tab2', 'tab3', 'tab4'];
    const tabContents = ['tab1Content', 'tab2Content', 'tab3Content', 'tab4Content'];
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



<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
<script type="text/javascript" src="../jsCalendar.js"></script>
<script type="text/javascript" src="../jsCalendar.lang.fr.js"></script>
</html>
