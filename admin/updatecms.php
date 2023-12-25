<?php
// Fichier de configuration
$configFile = __DIR__ . '/../config.json';

// URL du fichier de mise à jour sur GitHub
$githubFilesUrl = 'https://github.com/TallIceCreamCake/CMS-EVENT/archive/main.zip';

// Répertoire d'installation du CMS
$installationDir = __DIR__ . '/..';

// Fichier ZIP pour la mise à jour
$zipFile = 'main.zip';

// Fonction pour vérifier la connexion Internet
function checkInternetConnection()
{
    return @fsockopen('www.google.com', 80) !== false;
}

// Vérifier la connexion Internet
if (!checkInternetConnection()) {
    echo 'Pas de connexion Internet.';
    exit();
}

// Télécharger le fichier ZIP depuis GitHub
if (file_put_contents($zipFile, file_get_contents($githubFilesUrl)) === false) {
    echo 'Erreur lors du téléchargement du fichier ZIP depuis GitHub.';
    exit();
}

// Extraire le contenu du fichier ZIP dans un répertoire adjacent
$extractedDir = $installationDir . '/new_version';
$zip = new ZipArchive;

if ($zip->open($zipFile) === true) {
    // Créer le répertoire d'installation de la nouvelle version
    if (!mkdir($extractedDir, 0755, true)) {
        echo 'Erreur lors de la création du répertoire de la nouvelle version.';
        exit();
    }

    // Extraire le contenu du ZIP
    if (!$zip->extractTo($extractedDir)) {
        echo 'Erreur lors de l\'extraction du fichier ZIP.';
        exit();
    }

    // Fermer le fichier ZIP
    $zip->close();

    // Supprimer le fichier ZIP après l'extraction
    if (!unlink($zipFile)) {
        echo 'Erreur lors de la suppression du fichier ZIP après l\'extraction.';
        exit();
    }
} else {
    echo 'Erreur lors de l\'ouverture du fichier ZIP.';
    exit();
}

// Renommer le dossier dézippé
$newVersionDir = $installationDir . '/new_version/CMS-EVENT-main';
if (!rename($newVersionDir, $installationDir . '/new_version/cmsupdate')) {
    echo 'Erreur lors du renommage du dossier dézippé.';
    exit();
}

// Lire la version depuis le fichier dézippé
$newVersionConfigFile = $installationDir . '/new_version/cmsupdate/config.json';
if (file_exists($newVersionConfigFile)) {
    $newVersionData = json_decode(file_get_contents($newVersionConfigFile), true);
    $newVersion = isset($newVersionData['version']) ? $newVersionData['version'] : null;

    // Mettre à jour la version dans le fichier d'origine
    if ($newVersion) {
        $originalConfigData = json_decode(file_get_contents($configFile), true);
        $originalConfigData['version'] = $newVersion;
        file_put_contents($configFile, json_encode($originalConfigData, JSON_PRETTY_PRINT));
    }
}

// Local version
$local_version = $originalConfigData['version'];

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

// Check if an update is available
$update_available = (float)$github_version > (float)$local_version && !isset($_GET['update']);

// If an update is available, you can redirect or show a message
if ($update_available) {
    // Redirect with update parameter
    header('Location: index.php?update=1');
    exit();
}

// Effectuer la mise à jour
if (performUpdate($installationDir . '/new_version/cmsupdate')) {
    // Supprimer le répertoire new_version
    if (!removeDirectory($installationDir . '/new_version')) {
        echo 'Erreur lors de la suppression du répertoire new_version.';
        exit();
    }

    // Rediriger vers la page updatesuccessful.php
    header("Location: updatesuccessful.php");
    exit();
} else {
    echo 'Erreur lors de la mise à jour.';
}

// Fonction pour effectuer la mise à jour
function performUpdate($updateDir)
{
    // Déclarer $installationDir comme une variable globale
    global $installationDir;

    // Ajouter ici la logique de mise à jour (copier/supprimer des fichiers, mettre à jour la base de données, etc.)
    // ...

    // Exemple : Copier tous les fichiers de la nouvelle version vers le répertoire d'installation
    $files = glob($updateDir . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            $filename = basename($file);

            // Vérifier si le fichier est le fichier de configuration
            if ($filename === 'config.json') {
                // Ne pas copier le fichier de configuration
                continue;
            }

            $newLocation = $installationDir . '/' . $filename;

            if (!rename($file, $newLocation)) {
                echo 'Erreur lors du déplacement du fichier : ' . $filename;
                return false;
            }
        }
    }

    // Supprimer le répertoire de mise à jour
    if (!removeDirectory($updateDir)) {
        echo 'Erreur lors de la suppression du répertoire de mise à jour.';
        return false;
    }

    return true;
}

// Fonction pour supprimer un répertoire et son contenu
function removeDirectory($dir)
{
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!removeDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }

    return rmdir($dir);
}
?>
