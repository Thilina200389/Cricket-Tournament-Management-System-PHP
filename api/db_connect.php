<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "ctms_prov3";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection Failed: " . $conn->connect_error]));
}
?>