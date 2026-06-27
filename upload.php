<?php
include "db.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_FILES['file'])) {
    die("ERROR: No file received");
}

$fileName = $_FILES['file']['name'];
$tmpName = $_FILES['file']['tmp_name'];

if (!$tmpName) {
    die("ERROR: Temp file missing");
}

$extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
    $folder = __DIR__ . "/uploads/images/";
    $type = "image";
} elseif (in_array($extension, ['mp4', 'mov', 'avi', 'mkv', '3gp'])) {
    $folder = __DIR__ . "/uploads/videos/";
    $type = "video";
} else {
    die("ERROR: Invalid file type");
}

if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

$newFileName = time() . "_" . basename($fileName);
$target = $folder . $newFileName;

if (move_uploaded_file($tmpName, $target)) {

    $baseUrl =
        (isset($_SERVER['HTTPS']) ? "https://" : "http://")
        . $_SERVER['HTTP_HOST'];

    $relativePath =
        str_replace(__DIR__ . "/", "", $target);

    $url = $baseUrl . "/" . $relativePath;

    $result = pg_query_params(
        $conn,
        "INSERT INTO media_files (file_name, file_type, file_url)
         VALUES ($1, $2, $3)",
        array($newFileName, $type, $url)
    );

    if (!$result) {
        die("ERROR: Database insert failed");
    }

    echo "SUCCESS: Uploaded";

} else {
    echo "ERROR: move_uploaded_file failed";
}
?>