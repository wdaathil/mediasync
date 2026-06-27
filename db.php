<?php

$host = "db.brmipfwzwuxerzavixpo.supabase.co";
$port = "5432";
$dbname = "postgres";
$user = "postgres";
$password = "Aathil@10db";

$conn = pg_connect(
    "host=$host port=$port dbname=$dbname user=$user password=$password"
);

if (!$conn) {
    die("Connection failed");
}

echo "Connected successfully";

?>