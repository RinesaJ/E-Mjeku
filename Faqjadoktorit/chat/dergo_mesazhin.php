<?php
session_start();

// Check if the doctor is logged in
if (!isset($_SESSION['ID_Doktori'])) {
    echo "Përfundim i sesionit. Ju lutem kyçuni përsëri.";
    exit();
}

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

// Get the POST data
$doctor_id = $_SESSION['ID_Doktori'];
$user_id = $_POST['id_msg_dalse'];
$message = $_POST['msg'];
$sender_type = $_POST['tipi_derguesit']; // In this case, it will always be 'Doktori'

// Check if message is not empty
if (!empty($message)) {
    // Prepare SQL query to insert the message into the database
    $sql = "INSERT INTO mesazhet_chat (id_msg_hyrse, id_msg_dalse, msg) VALUES (?, ?, ?)";
    
    // Prepare statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameters
        $stmt->bind_param("iis", $doctor_id, $user_id, $message);
        
        // Execute the query
        if ($stmt->execute()) {
            echo "Mesazhi u dërgua me sukses";
        } else {
            echo "Gabim gjatë dërgimit të mesazhit";
        }
        
        // Close the statement
        $stmt->close();
    } else {
        echo "Gabim në përgatitjen e kërkesës: " . $conn->error;
    }
} else {
    echo "Mesazhi nuk mund të jetë i zbrazët.";
}

// Close the connection
$conn->close();
?>
