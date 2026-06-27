<?php

$host = "aws-1-ap-south-1.pooler.supabase.com";
$port = "6543";
$dbname = "postgres";
$user = "postgres.brmipfwzwuxerzavixpo";
$password = "Aathil@10db";

$conn = pg_connect(
    "host=$host port=$port dbname=$dbname user=$user password=$password"
);

if (!$conn) {
    die("Connection failed");
}

?>