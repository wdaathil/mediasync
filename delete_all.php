<?php
include "db.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch all image files
$result = pg_query(
    $conn,
    "SELECT * FROM media_files WHERE file_type='image'"
);

if ($result && pg_num_rows($result) > 0) {

    $baseUrl =
        (isset($_SERVER['HTTPS']) ? "https://" : "http://")
        . $_SERVER['HTTP_HOST'] . "/";

    while ($row = pg_fetch_assoc($result)) {

        // Convert URL to local file path
        $filePath = str_replace(
            $baseUrl,
            __DIR__ . "/",
            $row['file_url']
        );

        // Delete physical file
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // Delete database records
    pg_query(
        $conn,
        "DELETE FROM media_files WHERE file_type='image'"
    );
}

// Redirect back to gallery
header("Location: gallery.php");
exit;
?>