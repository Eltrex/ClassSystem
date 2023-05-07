<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: ../../index.php");
    exit();
}
if ($_SESSION["permission"] != "admin") {
    header("Location: ../../index.php");
    exit();
}

function Zip($source, $destination)
{
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file) {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if (in_array(substr($file, strrpos($file, '/') + 1), array('.', '..')))
                continue;

            $file = realpath($file);

            if (is_dir($file) === true) {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } else if (is_file($file) === true) {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    } else if (is_file($source) === true) {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}

$file_name = "Abgaben";

Zip('../users', $file_name . '.zip');

header('Content-Type: application/zip');
header('Content-disposition: attachment; filename=' . $file_name . '.zip');
header('Content-Length: ' . filesize($file_name . '.zip'));
readfile($file_name . '.zip');
unlink($file_name . '.zip');

$dir = '../users';
$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
$files = new RecursiveIteratorIterator($it,
             RecursiveIteratorIterator::CHILD_FIRST);
foreach($files as $file) {
    if ($file->isDir()){
        rmdir($file->getRealPath());
    } else {
        unlink($file->getRealPath());
    }
}
rmdir($dir);

if (!file_exists('../users')) {
    mkdir('../users', 0777, true);
}

$Taskfolder = "#Aufgaben";
if (!file_exists('../users/' . $Taskfolder)) {
    mkdir('../users/' . $Taskfolder, 0777, true);
}

require_once '../../inc/db.php';

$stmt = $pdo->prepare("UPDATE students SET HANDED_IN = 0");
$stmt->execute();

header("Location: dashboard.php");