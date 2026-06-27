<?php
include "db.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$zipName = __DIR__ . "/all_images_" . time() . ".zip";
$zip = new ZipArchive();

if ($zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    die("Failed to create ZIP file");
}

$result = pg_query(
    $conn,
    "SELECT * FROM media_files WHERE file_type='image'"
);

if (!$result || pg_num_rows($result) == 0) {
    die("No images found");
}

$fileCount = 0;

$baseUrl =
    (isset($_SERVER['HTTPS']) ? "https://" : "http://")
    . $_SERVER['HTTP_HOST'] . "/";

while ($row = pg_fetch_assoc($result)) {

    // Convert stored URL to local file path
    $filePath = str_replace(
        $baseUrl,
        __DIR__ . "/",
        $row['file_url']
    );

    if (file_exists($filePath)) {
        $zip->addFile(
            $filePath,
            basename($filePath)
        );
        $fileCount++;
    }
}

$zip->close();

if ($fileCount == 0) {
    if (file_exists($zipName)) {
        unlink($zipName);
    }
    die("No valid files found for ZIP");
}

header('Content-Type: application/zip');
header(
    'Content-Disposition: attachment; filename="' .
    basename($zipName) .
    '"'
);
header('Content-Length: ' . filesize($zipName));
header('Pragma: no-cache');
header('Expires: 0');

readfile($zipName);

if (file_exists($zipName)) {
    unlink($zipName);
}

exit;
?>