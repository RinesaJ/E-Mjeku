<?php
include_once '../konfigurimi.php';


if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM specializimi WHERE ID_Specializimi = ?");
    if (!$stmt) {
        $_SESSION['error_message'] = 'Gabim gjatë përgatitjes së pyetjes: ' . $conn->error;
        header("Location: specializimi.php");
        exit;
    }
    
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Specializimi u fshi me sukses!';
    } else {
        $_SESSION['error_message'] = 'Gabim gjatë fshirjes: ' . $stmt->error;
    }
    
    $stmt->close();
    header("Location: specializimi.php"); 
    exit;
} else {
    $_SESSION['error_message'] = 'ID e specializimit nuk është e vlefshme.';
    header("Location: specializimi.php");
    exit;
}
?>
