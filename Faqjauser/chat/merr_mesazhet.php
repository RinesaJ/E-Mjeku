<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['ID_Perdoruesi'])) {
    die("Access denied!");
}

// Database connection
$host = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "smd";

$conn = new mysqli($host, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the incoming ID (doctor ID)
$incoming_id = intval($_GET['incoming_id']);

// Fetch messages between the user and the doctor
$sql = "SELECT * FROM mesazhet_chat 
        WHERE (id_msg_hyrse = ? AND id_msg_dalse = ?) 
        OR (id_msg_hyrse = ? AND id_msg_dalse = ?) 
        ORDER BY koha ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $_SESSION['ID_Perdoruesi'], $incoming_id, $incoming_id, $_SESSION['ID_Perdoruesi']);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
$stmt->close();
$conn->close();
?>