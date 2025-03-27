<?php
session_start();
include_once '../konfigurimi.php';

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $_SESSION['error_message'] = 'ID e doktorit mungon ose nuk është e vlefshme.';
    header("Location: doktoret.php");
    exit;
}

$id = intval($_GET['id']);


if ($stmt = $conn->prepare("DELETE FROM doktoret WHERE ID_Doktori = ?")) {
    $stmt->bind_param("i", $id); 

    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Doktori u fshi me sukses!';
    } else {
        $_SESSION['error_message'] = 'Gabim gjatë fshirjes: ' . $stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['error_message'] = 'Gabim ne databaze: ' . $conn->error;
}

header("Location: doktoret.php");
exit;
?>
