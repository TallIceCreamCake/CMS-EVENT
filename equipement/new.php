
<?php
session_start();
if (empty($_SESSION['username'])) {
    header("Location: ../login.php");
    exit(); 
}
// ------------------- connexion DB
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
//------------------ FIN CONNEXION DB

if ($config_data['isconfig?'] === "false") {
  header("Location: ../installation");
}

?>
<!-- Le reste de votre code HTML reste inchangé -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>*Nouvelle équipement</title>
    <!-- Importer le fichier de style Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body style='background:#fff;'>
<?php 
            $basePath = '../'; 
            include('../components/navbar1.php');
            include('../components/navbar2.php');
        ?>
    <div class="mx-auto lg:ml-80">
        <div class="ml-[5rem] mr-[5rem]">
    <h1 class="text-2xl font-bold mt-[2rem] mb-[2rem]">Création d'une nouvelle fiche d'équipement</h1>
    <form action="newprocess.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="reservation_id" value="<?php echo $reservation_id; ?>">

            <div class="relative z-0 w-full mb-5 group">
        <input type="text" id="nom" name="nom" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
        <label for="nom" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nom</label>
    </div>
    <div class="form-group">
            <label for="underline_select" class="sr-only">Underline select</label>
            <select onchange="updateSubcategory()" id="type" name="type" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
            <option value="son">Son</option>
                    <option value="lumiere">Lumière</option>
            </select>
            </div><br>
            <div class="form-group" id="subCategory1" style="display: none;">
            <label for="underline_select" class="sr-only">Underline select</label>
            <select name="subtype1" id="subtype1" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
            <option value="table de mixage">Table de Mixage</option>
                    <option value="diffusion">Diffusions</option>
                    <option value="micro">Micros</option>
                    <option value="systeme hf">Système HF</option>
                    <option value="amplis">Amplis</option>
                    <option value="cables">Cables</option>
                    <option value="pieds/supports">Pieds/Supports</option>
                    <option value="autre">Autre</option>
            </select>
            </div><br>
            <div class="form-group" id="subCategory2" style="display: none;">
            <label for="underline_select" class="sr-only">Underline select</label>
            <select name="subtype2" id="subtype2" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
            <option value="wash">Wash</option>
                    <option value="spot">Spot</option>
                    <option value="beam">Beam</option>
                    <option value="projecteur">Projecteur</option>
                    <option value="tradi">Tradi</option>
                    <option value="grada">Grada</option>
                    <option value="rideaux leds et accessoires">Rideaux leds et accessoires</option>
                    <option value="console lumière">Console Lumière</option>
                    <option value="cables">Cables</option>
                    <option value="autre">Autre</option>
            </select>
            </div><br>
            <input type="hidden" name="subtype1" id="subtype1" value="">
<input type="hidden" name="subtype2" id="subtype2" value="">
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
        <button type="button" id="add-stock" class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none">
            <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
            </svg>
        </button>
    </div>
  </div>
    <div id="additional-stocks"></div><br>
            <div class="flex items-center justify-center w-full">
    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
        <div class="flex flex-col items-center justify-center pt-5 pb-6">
            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
            </svg>
            <p id="file-label" class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Cliquez pour transférer</span> ou jetez votre fichier</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">PNG ou JPG</p>
        </div>
        <input id="dropzone-file" type="file" name="file" class="hidden" accept="image/png, image/jpeg" onchange="updateFileLabel(this)" />
    </label>
</div>

<script>
    function updateFileLabel(input) {
        const fileLabel = document.getElementById('file-label');
        if (input.files.length > 0) {
            fileLabel.textContent = `Fichier sélectionné : ${input.files[0].name}`;
        } else {
            fileLabel.textContent = "Cliquez pour transférer ou jetez votre fichier";
        }
    }
</script> <br>
            <button type="submit" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Créer la fiche matos</button>
        </form>
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
    const typeSelect = document.getElementById("type");
    const subCategory1 = document.getElementById("subCategory1");
    const subCategory2 = document.getElementById("subCategory2");
    const subType1 = document.getElementById("subtype1");
    const subType2 = document.getElementById("subtype2");

    if (typeSelect.value === "son") {
        subCategory1.style.display = "block";
        subCategory2.style.display = "none";
        subType1.name = "soustype"; // Changer le nom du champ ici
        subType2.name = "";
    } else if (typeSelect.value === "lumiere") {
        subCategory1.style.display = "none";
        subCategory2.style.display = "block";
        subType1.name = ""; // Assurez-vous que le nom du champ est vide
        subType2.name = "soustype"; // Changer le nom du champ ici
    } else {
        subCategory1.style.display = "none";
        subCategory2.style.display = "none";
        subType1.name = "";
        subType2.name = "";
    }
}

updateSubcategory(); // Appeler la fonction pour afficher la sous-catégorie au chargement de la page.

</script>
<script>
    // Fonction pour supprimer un champ d'entrepôt
    function removeStockField(event) {
        const stockField = event.target.closest('.additional-stock-field');
        if (stockField) {
            stockField.remove();
        }
    }

    // Ajoutez dynamiquement un nouveau champ d'entrepôt lorsqu'on clique sur le bouton "add-stock"
    document.getElementById("add-stock").addEventListener("click", function () {
        const stockContainer = document.getElementById("additional-stocks");
        const newStockField = document.createElement("div");
        newStockField.className = 'additional-stock-field'; // Ajoutez une classe pour identifier le champ
        newStockField.innerHTML = `
            <div class="relative flex items-center ">
                <select name="stock[]" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer" required>
                    <option value="" disabled selected>Choisir le stock</option>
                    <?php
                    // Utilisez votre connexion existante à la base de données
                    // Assurez-vous que votre connexion est stockée dans la variable $votre_connexion_mysql
                    // Remplacez 'VotreTableEntrepot' par le nom réel de votre table
                    // Remplacez 'id_entrepot' et 'nom_entrepot' par les colonnes réelles de votre table

                    $query_entrepots = "SELECT * FROM entrepot";
                    $result_entrepots = mysqli_query($db, $query_entrepots);

                    // Récupérez les entrepôts déjà sélectionnés
                    $selectedEntrepots = [];
                    if (isset($_POST['stock'])) {
                        $selectedEntrepots = $_POST['stock'];
                    }

                    // Affichez les options dans le menu déroulant
                    while ($row_entrepot = mysqli_fetch_assoc($result_entrepots)) {
                        // Vérifiez si l'entrepôt est déjà sélectionné
                        $selected = in_array($row_entrepot['id'], $selectedEntrepots) ? 'disabled' : '';
                        echo "<option value='{$row_entrepot['id']}' {$selected}>{$row_entrepot['nom']}</option>";
                    }
                    ?>
                </select>
                <input type="text" name="quantity_stock[]" data-input-counter aria-describedby="helper-text-explanation" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder="Quantité" required>
                <button type="button" onclick="removeStockField(event)" class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none">
                    <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div><br>
        `;
        stockContainer.appendChild(newStockField);
    });
</script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>

</html>
