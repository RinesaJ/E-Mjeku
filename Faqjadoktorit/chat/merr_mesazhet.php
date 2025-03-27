<?php
session_start();

// Check if GET data is set
if (!isset($_GET['id_msg_hyrse']) || !isset($_GET['id_msg_dalse'])) {
    die("Të dhënat e kërkuara për marrjen e mesazheve mungojnë.");
}

// Get GET data and sanitize it
$id_msg_hyrse = intval($_GET['id_msg_hyrse']);
$id_msg_dalse = intval($_GET['id_msg_dalse']);

// Database connection
$host = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "smd";

$conn = new mysqli($host, $dbUser, $dbPassword, $dbName);

// Check for connection errors
if ($conn->connect_error) {
    die("Gabim në lidhje me bazën e të dhënave: " . $conn->connect_error);
}

// Fetch messages from the database
$sql = "SELECT * FROM mesazhet_chat WHERE (id_msg_hyrse = ? AND id_msg_dalse = ?) OR (id_msg_hyrse = ? AND id_msg_dalse = ?) ORDER BY koha ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $id_msg_hyrse, $id_msg_dalse, $id_msg_dalse, $id_msg_hyrse);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($messages);
?>