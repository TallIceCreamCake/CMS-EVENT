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
    <script src="../tailwind.js"></script>
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
                  <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="../">
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
                  <a class="flex items-center pl-3 py-3 pr-2 text-gray-50 hover:bg-gray-900 rounded" href="../prestation/index.php">
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
                  <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="#">
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
    <header class="bg-gray-50 mx-auto lg:ml-80">
    <nav class="bg-white border-gray-200 dark:bg-gray-900 dark:border-gray-700">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Bienvenue sur le panel</span>
    </a>
    <button data-collapse-toggle="navbar-multi-level" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-multi-level" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
    </button>
    <div class="hidden w-full md:block md:w-auto" id="navbar-multi-level">
      <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
        <li>
            <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar" class="flex items-center justify-between w-full py-2 px-3 text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-white md:dark:hover:text-blue-500 dark:focus:text-white dark:hover:bg-gray-700 md:dark:hover:bg-transparent">
            <img id="avatarButton" type="button" data-dropdown-toggle="userDropdown" data-dropdown-placement="bottom-start" class="w-10 h-10 rounded-full cursor-pointer" src="../ppclients/basic.png" alt="User dropdown">

    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
  </svg></button>
            <!-- Dropdown menu -->
            <div id="dropdownNavbar" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
            <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
      <div><?php echo $users['prenom'] . ' ' . $users['nom'];?></div>
      <div class="font-medium truncate"><?php echo $users['mail'];?></div>
    </div>
    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="avatarButton">
      <li>
        <a href="./compte/" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Paramètre </a>
      </li>
      <li>
        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Paramètre CMS</a>
      </li>
    </ul>
    <div class="py-1">
      <a href="index.php?deconnexion=true" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Déconnexion</a>
    </div>
            </div>
        </li>
      </ul>
    </div>
  </div>
</nav>

  </div>
</header>


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
    
    <div class="basis-1/3 w-1/3 flex-1 mr-[5rem] ml-[1rem] border rounded p-4 shadow-md">
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
