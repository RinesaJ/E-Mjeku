<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smd";

session_start();

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Gabim ne lidhje: " . $conn->connect_error);
}
?>