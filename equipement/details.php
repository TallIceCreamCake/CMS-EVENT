<?php
session_start();
if (empty($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['materiel_id'])) {
    $materiel_id = $_GET['materiel_id'];

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
    // Récupérez les détails de la réservation en fonction de $clients_id
    $query = $db->prepare("SELECT * FROM materiel WHERE id = ?");
    $query->bind_param("i", $materiel_id);
    $query->execute();
    $result = $query->get_result();
    $matos_detail = $result->fetch_assoc();
}

?>

<!-- Le reste de votre code HTML reste inchangé -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fiche d'equipement</title>
    <!-- Importer le fichier de style Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="../jsCalendar.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
                .hidden {
            display: none;
        }
    </style>
</head>
<body style='background:#fff;'>
<?php 
            $basePath = '../'; 
            include('../components/navbar1.php');
            include('../components/navbar2.php');
        ?>
    <div class="mx-auto lg:ml-80">
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
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Etes vous sur de vouloir supprimer cet équipement ?</h3>
                    <form action="delete.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $clients_detail['id']; ?>">
                        <button data-modal-hide="popup-modal" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">Oui</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="basis-1/4 flex-0 ml-[1rem] mr-[10rem]  p-4">
    <h1 class="text-2xl font-bold mt-[2rem] mb-[1rem]">Détail de l'équipement n°<?php echo $matos_detail['id']; ?></h1>

    <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px">
            <li class="me-2">
                <a id="tab1" href="#tab1" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500">Général</a>
            </li>
            <li class="me-2">
                <a id="tab2" href="#tab2" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" aria-current="page">Stock</a>
            </li>
        </ul>
    </div>
    <div id="tab1Content" class="hidden">
    <div class="mx-auto my-10 flex flex-wrap flex-row">


    <div class="basis-1/2 w-1/2 flex-1 mr-[0rem] border rounded p-4 shadow-md">
    <h1 class="text-3xl font-bold mb-8 ">Informations général</h1>
    <form class="" action="editprocess.php" method="post">
            <input type="hidden" name="clients_id" value="<?php echo $matos_detail['id']; ?>">

        <div class="relative z-0 w-full mb-5 group">
                <input type="text" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" id="entreprise" name="entreprise" value="<?php echo $matos_detail['nom']; ?>" required>
                <label for="nom" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nom :</label>
            </div>   
            <button type="submit" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Enregistrer les modifications</button>
        </form>
</div>

<div class="basis-1/6 w-1/6 flex-1 ml-[1rem] border rounded p-4 shadow-md">
<div class="rounded-t-lg w-50 pr-1 py-4 shadow " style="background-color: #333"><br>
        <!-- Vous pouvez ajouter du contenu supplémentaire ici si nécessaire -->
    </div><br>
<div class="flex flex-col items-center pb-10">

<img class="rounded-t-lg" src="../images/imagematos/<?php echo $matos_detail['image'] ?>" alt="" />
        <h1 class="mb-1 text-xl font-medium text-gray-900 dark:text-white"><?php echo $matos_detail['nom']; ?></h1>
        <span class="text-sm text-gray-500 dark:text-gray-400"></span>
        <div class="flex mt-4 md:mt-6">
        <button data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-red-500 rounded-lg hover:bg-gray-100 hover:text-red-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
    Supprimer
  </button>        </div>
</div>
      </div>
      </div>
      </div>


      </div>
      <div id="tab2Content" class="hidden">
    <div class="basis-1/4 flex-0 ml-[1rem] mr-[10rem] p-4">
      

<!-- Modal toggle -->
<button data-modal-target="crud-modal" data-modal-toggle="crud-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
  Ajouter du stock
</button>
<!-- Main modal -->
<div id="crud-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Ajouter du stock
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form class="p-4 md:p-5" action="addstockprocess.php" method="post">
            <input type="hidden" name="matos_id" value="<?php echo $matos_detail['id']; ?>">
            <input type="hidden" name="matos_name" value="<?php echo $matos_detail['nom']; ?>">
            <div class="relative z-0 w-full mb-5 group">
    <label for="stock" class="block  text-sm font-medium text-gray-900 dark:text-white">Choisir le stock :</label>
    <div class="relative flex items-center">
    <select name="stock" id="stock" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer" required>
    <option value="" disabled selected>Choisir l'entrepôt</option>
    <?php
    // Utilisez votre connexion existante à la base de données
    // Assurez-vous que votre connexion est stockée dans la variable $votre_connexion_mysql
    // Remplacez 'VotreTableEntrepot' par le nom réel de votre table
    // Remplacez 'id_entrepot' et 'nom_entrepot' par les colonnes réelles de votre table

    $query_entrepots = "SELECT * FROM entrepot";
    $result_entrepots = mysqli_query($db, $query_entrepots);

    // Affichez les options dans le menu déroulant
    while ($row_entrepot = mysqli_fetch_assoc($result_entrepots)) {
        echo "<option value='{$row_entrepot['id']}'>{$row_entrepot['nom']}</option>";
    }
    ?>
</select>
        <input type="text" id="quantity_stock" name="quantity_stock" data-input-counter aria-describedby="helper-text-explanation" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder="Quantité" required>
    </div>
  </div> <br>
            <button type="submit" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Ajouter le stock</button>
        </form>
        </div>
    </div>
</div> 
<br>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <?php
$query_stock = $db->prepare("SELECT * FROM warehouse_stock WHERE id_equipment = ?");
$query_stock->bind_param("i", $matos_detail['id']);
$query_stock->execute();
$result_stock = $query_stock->get_result();

// Vérifiez si $result_stock contient des lignes
if ($result_stock->num_rows > 0) {
    ?>
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    N°
                </th>
                <th scope="col" class="px-6 py-3">
                    NOM
                </th>
                <th scope="col" class="px-6 py-3">
                    QUANTITE
                </th>
                <th scope="col" class="px-6 py-3">
                    ACTION
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Boucle à travers les résultats de l'entrepôt
            while ($row_stock = $result_stock->fetch_assoc()) {
                $id_warehouse = $row_stock['id_warehouse'];

                $query_warehouse = $db->prepare("SELECT * FROM entrepot WHERE id = ?");
                $query_warehouse->bind_param("i", $id_warehouse);
                $query_warehouse->execute();
                $result_warehouse = $query_warehouse->get_result();

                // Vérifiez si $result_warehouse contient des lignes
                if ($result_warehouse->num_rows > 0) {
                    $row_warehouse = $result_warehouse->fetch_assoc();
                    echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">';
                    echo '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">' . $row_warehouse['id'] . '</td>';
                    echo '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">' . $row_warehouse['nom'] . '</td>';
                    echo '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">' . $row_stock['nb_stockequipment'] . '</td>';
                    echo '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white ">
                        <a href="../entrepot/details.php?entrepot=' . $row_warehouse['id'] . '" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-5">Voir les détails</a>  
                        </td>';
                    
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
<?php
} else {
    echo '<p class="text-3xl text-gray-900 dark:text-white mt-[5rem] mb-[5rem] text-center">Erreur lors de la récupération du stock veuillez attribuer un stock</p>';
}
?>


        </div>
    </div>
</div>


</div>
</body>





<script>
    const tabs = ['tab1', 'tab2'];
    const tabContents = ['tab1Content', 'tab2Content'];
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
