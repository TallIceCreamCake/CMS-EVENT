
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
<div data-section-id="1" data-share="" data-category="ta-menus" data-component-id="10070f9f_01_awz" data-container="1">
<div class="" id="content">
<nav class="lg:hidden py-6 px-6 bg-gray-800">
          <div class="flex items-center justify-between">
            <a class="text-2xl text-white font-semibold" href="#" data-config-id="brand">
              <img class="h-10" src="artemis-assets/logos/artemis-logo.svg" alt="" width="auto">
            </a>
            <button class="navbar-burger flex items-center rounded focus:outline-none">
              <svg class="text-white bg-indigo-500 hover:bg-indigo-600 block h-8 w-8 p-2 rounded" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="currentColor" data-config-id="auto-svg-1-1">
                <title>Mobile menu</title>
                <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
              </svg>
            </button>
          </div>
        </nav>
        <div class="hidden lg:block navbar-menu relative z-50">
          <div class="navbar-backdrop fixed lg:hidden inset-0 bg-gray-800 opacity-10"></div>
          <nav class="fixed top-0 left-0 bottom-0 flex flex-col w-3/4 lg:w-80 sm:max-w-xs pt-6 pb-8 bg-gray-800 overflow-y-auto">
            <div class="flex w-full items-center px-6 pb-6 mb-6 lg:border-b border-gray-700">
              <a class="text-xl text-white font-semibold" href="#" data-config-id="brand">
                <img class="h-8" src="artemis-assets/logos/artemis-logo.svg" alt="" width="auto"> <?php echo $config_data['name']; ?>
              </a>
            </div>
            <div class="px-4 pb-6">
              <h3 class="mb-2 text-xs uppercase text-gray-500 font-medium" data-config-id="header1">Main</h3>
              <ul class="mb-8 text-sm font-medium">
                <li>
                  <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="../index.php">
                    <span class="inline-block mr-3">
                        <img class="text-indigo-100 w-5 h-5" viewbox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" data-config-id="auto-svg-2-1" src="../icon/maison.svg" alt="">
                    </span>
                    <span data-config-id="link1">Tableau de bord</span>
                    <span class="inline-block ml-auto">
                      <svg class="text-gray-400 w-3 h-3" viewbox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg" data-config-id="auto-svg-3-1">
                        <path d="M9.08329 0.666626C8.74996 0.333293 8.24996 0.333293 7.91663 0.666626L4.99996 3.58329L2.08329 0.666626C1.74996 0.333293 1.24996 0.333293 0.916626 0.666626C0.583293 0.999959 0.583293 1.49996 0.916626 1.83329L4.41663 5.33329C4.58329 5.49996 4.74996 5.58329 4.99996 5.58329C5.24996 5.58329 5.41663 5.49996 5.58329 5.33329L9.08329 1.83329C9.41663 1.49996 9.41663 0.999959 9.08329 0.666626Z" fill="currentColor"></path>
                      </svg>
                    </span>
                  </a>
                </li>
                <li>
                  <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="#">
                    <span class="inline-block mr-3">
                      <img class="text-gray-600 w-5 h-5" viewbox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" data-config-id="auto-svg-5-1" src="../icon/calendrier.svg" alt="">
                    </span>
                    <span data-config-id="link3">Mon agenda</span>
                    <span class="inline-block ml-auto">
                      <svg class="text-gray-400 w-3 h-3" viewbox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg" data-config-id="auto-svg-6-1">
                        <path d="M9.08329 0.666626C8.74996 0.333293 8.24996 0.333293 7.91663 0.666626L4.99996 3.58329L2.08329 0.666626C1.74996 0.333293 1.24996 0.333293 0.916626 0.666626C0.583293 0.999959 0.583293 1.49996 0.916626 1.83329L4.41663 5.33329C4.58329 5.49996 4.74996 5.58329 4.99996 5.58329C5.24996 5.58329 5.41663 5.49996 5.58329 5.33329L9.08329 1.83329C9.41663 1.49996 9.41663 0.999959 9.08329 0.666626Z" fill="currentColor"></path>
                      </svg>
                    </span>
                  </a>
                </li>
                <li>
                  <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="../entrepot/index.php">
                    <span class="inline-block mr-3">
                      <img class="text-gray-600 w-5 h-5" viewbox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" data-config-id="auto-svg-5-1" src="../icon/entrepot.svg" alt="">
                    </span>
                    <span data-config-id="link3">Entrepots</span>
                    <span class="inline-block ml-auto">
                      <svg class="text-gray-400 w-3 h-3" viewbox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg" data-config-id="auto-svg-6-1">
                        <path d="M9.08329 0.666626C8.74996 0.333293 8.24996 0.333293 7.91663 0.666626L4.99996 3.58329L2.08329 0.666626C1.74996 0.333293 1.24996 0.333293 0.916626 0.666626C0.583293 0.999959 0.583293 1.49996 0.916626 1.83329L4.41663 5.33329C4.58329 5.49996 4.74996 5.58329 4.99996 5.58329C5.24996 5.58329 5.41663 5.49996 5.58329 5.33329L9.08329 1.83329C9.41663 1.49996 9.41663 0.999959 9.08329 0.666626Z" fill="currentColor"></path>
                      </svg>
                    </span>
                  </a>
                </li>
              </ul>
              <h3 class="mb-2 text-xs uppercase text-gray-500 font-medium" data-config-id="header2">Secondary</h3>
              <ul class="text-sm font-medium">
                <li>
                  <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="../prestation/index.php">
                    <span class="inline-block mr-3">
                    <img class="text-gray-600 w-5 h-5" viewbox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" data-config-id="auto-svg-5-1" src="../icon/carnet.svg" alt="">
                    </span>
                    <span data-config-id="link7">Prestation</span>
                  </a>
                </li>
                <li>
                  <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="../clients/index.php">
                    <span class="inline-block mr-3">
                      <svg class="text-gray-600 w-5 h-5" viewbox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" data-config-id="auto-svg-14-1">
                      <path d="M12,12A6,6,0,1,0,6,6,6.006,6.006,0,0,0,12,12ZM12,2A4,4,0,1,1,8,6,4,4,0,0,1,12,2Z" fill="currentColor"/><path d="M12,14a9.01,9.01,0,0,0-9,9,1,1,0,0,0,2,0,7,7,0,0,1,14,0,1,1,0,0,0,2,0A9.01,9.01,0,0,0,12,14Z" fill="currentColor"/>
                      </svg>
                    </span>
                    <span data-config-id="link8">Clients</span>
                  </a>
                </li>
                <li>
                  <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="../equipement">
                    <span class="inline-block mr-3">
                      <svg class="text-gray-600 w-5 h-5" viewbox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg" data-config-id="auto-svg-15-1">
                        <path d="M18.9831 6.64169C18.9047 6.545 18.8056 6.46712 18.6931 6.41376C18.5806 6.36041 18.4576 6.33293 18.3331 6.33335H16.6665V5.50002C16.6665 4.83698 16.4031 4.20109 15.9342 3.73225C15.4654 3.26341 14.8295 3.00002 14.1665 3.00002H8.93313L8.66646 2.16669C8.49359 1.67771 8.17292 1.2546 7.74888 0.955986C7.32484 0.657367 6.81843 0.498019 6.2998 0.500019H3.33313C2.67009 0.500019 2.0342 0.763411 1.56536 1.23225C1.09652 1.70109 0.83313 2.33698 0.83313 3.00002V13C0.83313 13.6631 1.09652 14.2989 1.56536 14.7678C2.0342 15.2366 2.67009 15.5 3.33313 15.5H15.3331C15.9008 15.4984 16.451 15.3036 16.8933 14.9476C17.3355 14.5917 17.6435 14.0959 17.7665 13.5417L19.1665 7.35002C19.1918 7.22578 19.1885 7.0974 19.1567 6.97466C19.1249 6.85191 19.0656 6.73803 18.9831 6.64169ZM4.4748 13.1834C4.43246 13.3713 4.32629 13.5388 4.17435 13.6574C4.02241 13.7759 3.8341 13.8381 3.64146 13.8334H3.33313C3.11212 13.8334 2.90015 13.7456 2.74387 13.5893C2.58759 13.433 2.4998 13.221 2.4998 13V3.00002C2.4998 2.779 2.58759 2.56704 2.74387 2.41076C2.90015 2.25448 3.11212 2.16669 3.33313 2.16669H6.2998C6.48152 2.1572 6.66135 2.20746 6.81183 2.30978C6.9623 2.4121 7.07515 2.56087 7.13313 2.73335L7.58313 4.10002C7.6366 4.25897 7.7368 4.39809 7.8706 4.49919C8.00441 4.60029 8.16561 4.65867 8.33313 4.66669H14.1665C14.3875 4.66669 14.5994 4.75448 14.7557 4.91076C14.912 5.06704 14.9998 5.27901 14.9998 5.50002V6.33335H6.66646C6.47383 6.32864 6.28551 6.39084 6.13358 6.50935C5.98164 6.62786 5.87546 6.79537 5.83313 6.98335L4.4748 13.1834ZM16.1415 13.1834C16.0991 13.3713 15.993 13.5388 15.841 13.6574C15.6891 13.7759 15.5008 13.8381 15.3081 13.8334H6.00813C6.05117 13.7405 6.08198 13.6425 6.0998 13.5417L7.33313 8.00002H17.3331L16.1415 13.1834Z" fill="currentColor"></path>
                      </svg>
                    </span>
                    <span data-config-id="link9">Materiel</span>
                  </a>
                </li>
                <li>
                  <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="#">
                    <span class="inline-block mr-3">
                      <svg class="text-gray-600 w-5 h-5" viewbox="0 0 18 10" fill="none" xmlns="http://www.w3.org/2000/svg" data-config-id="auto-svg-16-1">
                        <path d="M2.09182 8.575C2.01257 8.49913 1.91911 8.43966 1.81682 8.4C1.61394 8.31665 1.38637 8.31665 1.18349 8.4C1.08119 8.43966 0.98774 8.49913 0.908486 8.575C0.832619 8.65425 0.773148 8.74771 0.733486 8.85C0.66967 9.00176 0.652235 9.16902 0.68338 9.33068C0.714525 9.49234 0.792855 9.64115 0.908486 9.75833C0.989487 9.83194 1.0825 9.89113 1.18349 9.93333C1.28324 9.97742 1.39109 10.0002 1.50015 10.0002C1.60921 10.0002 1.71707 9.97742 1.81682 9.93333C1.91781 9.89113 2.01082 9.83194 2.09182 9.75833C2.20745 9.64115 2.28578 9.49234 2.31693 9.33068C2.34807 9.16902 2.33064 9.00176 2.26682 8.85C2.22716 8.74771 2.16769 8.65425 2.09182 8.575ZM4.83349 1.66667H16.5002C16.7212 1.66667 16.9331 1.57887 17.0894 1.42259C17.2457 1.26631 17.3335 1.05435 17.3335 0.833333C17.3335 0.61232 17.2457 0.400358 17.0894 0.244078C16.9331 0.0877975 16.7212 0 16.5002 0H4.83349C4.61247 0 4.40051 0.0877975 4.24423 0.244078C4.08795 0.400358 4.00015 0.61232 4.00015 0.833333C4.00015 1.05435 4.08795 1.26631 4.24423 1.42259C4.40051 1.57887 4.61247 1.66667 4.83349 1.66667ZM2.09182 4.40833C1.97463 4.2927 1.82582 4.21437 1.66416 4.18323C1.50251 4.15208 1.33525 4.16952 1.18349 4.23333C1.0825 4.27554 0.989487 4.33472 0.908486 4.40833C0.832619 4.48759 0.773148 4.58104 0.733486 4.68333C0.689399 4.78308 0.666626 4.89094 0.666626 5C0.666626 5.10906 0.689399 5.21692 0.733486 5.31667C0.775688 5.41765 0.834877 5.51067 0.908486 5.59167C0.989487 5.66528 1.0825 5.72447 1.18349 5.76667C1.28324 5.81075 1.39109 5.83353 1.50015 5.83353C1.60921 5.83353 1.71707 5.81075 1.81682 5.76667C1.91781 5.72447 2.01082 5.66528 2.09182 5.59167C2.16543 5.51067 2.22462 5.41765 2.26682 5.31667C2.31091 5.21692 2.33368 5.10906 2.33368 5C2.33368 4.89094 2.31091 4.78308 2.26682 4.68333C2.22716 4.58104 2.16769 4.48759 2.09182 4.40833ZM16.5002 4.16667H4.83349C4.61247 4.16667 4.40051 4.25446 4.24423 4.41074C4.08795 4.56703 4.00015 4.77899 4.00015 5C4.00015 5.22101 4.08795 5.43298 4.24423 5.58926C4.40051 5.74554 4.61247 5.83333 4.83349 5.83333H16.5002C16.7212 5.83333 16.9331 5.74554 17.0894 5.58926C17.2457 5.43298 17.3335 5.22101 17.3335 5C17.3335 4.77899 17.2457 4.56703 17.0894 4.41074C16.9331 4.25446 16.7212 4.16667 16.5002 4.16667ZM2.09182 0.241667C2.01257 0.165799 1.91911 0.106329 1.81682 0.0666666C1.66506 0.00285041 1.4978 -0.0145849 1.33614 0.0165602C1.17448 0.0477053 1.02567 0.126035 0.908486 0.241667C0.834877 0.322667 0.775688 0.415679 0.733486 0.516667C0.689399 0.616417 0.666626 0.724274 0.666626 0.833333C0.666626 0.942392 0.689399 1.05025 0.733486 1.15C0.775688 1.25099 0.834877 1.344 0.908486 1.425C0.989487 1.49861 1.0825 1.5578 1.18349 1.6C1.33525 1.66382 1.50251 1.68125 1.66416 1.65011C1.82582 1.61896 1.97463 1.54063 2.09182 1.425C2.16543 1.344 2.22462 1.25099 2.26682 1.15C2.31091 1.05025 2.33368 0.942392 2.33368 0.833333C2.33368 0.724274 2.31091 0.616417 2.26682 0.516667C2.22462 0.415679 2.16543 0.322667 2.09182 0.241667ZM16.5002 8.33333H4.83349C4.61247 8.33333 4.40051 8.42113 4.24423 8.57741C4.08795 8.73369 4.00015 8.94565 4.00015 9.16667C4.00015 9.38768 4.08795 9.59964 4.24423 9.75592C4.40051 9.9122 4.61247 10 4.83349 10H16.5002C16.7212 10 16.9331 9.9122 17.0894 9.75592C17.2457 9.59964 17.3335 9.38768 17.3335 9.16667C17.3335 8.94565 17.2457 8.73369 17.0894 8.57741C16.9331 8.42113 16.7212 8.33333 16.5002 8.33333Z" fill="currentColor"></path>
                      </svg>
                    </span>
                    <span data-config-id="link10">Personnel</span>
                  </a>
                </li>
              </ul>
            </div>
          </nav>
    </div>
    </div>
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
