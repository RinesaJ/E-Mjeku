<?php
include_once '../konfigurimi.php';

if (!isset($_SESSION["Perdoruesi"])) {
    header("location: ../kycu.php");
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $conn->begin_transaction();

    try {
        // Fshi mesazhet e përdoruesit
        $deleteMessagesQuery = $conn->prepare("DELETE FROM perdoruesit WHERE ID_Perdoruesi = ?");
        $deleteMessagesQuery->bind_param("ii", $user_id, $user_id);
        $deleteMessagesQuery->execute();

        // Fshi përdoruesin
        $deleteUserQuery = $conn->prepare("DELETE FROM perdoruesit WHERE ID_Perdoruesi = ?");
        $deleteUserQuery->bind_param("i", $user_id);
        $deleteUserQuery->execute();

        $conn->commit();

        $_SESSION['success_message'] = "Përdoruesi u fshi me sukses!";
        header("Location: perdoruesit.php");
        exit;
    } catch (Exception $e) {
        $conn->rollback();

        $_SESSION['error_message'] = "Gabim gjatë fshirjes: " . $e->getMessage();
        header("Location: perdoruesit.php");
        exit;
    }
} else {
    $_SESSION['error_message'] = "Përdoruesi nuk është specifikuar!";
    header("Location: perdoruesit.php");
    exit;
}
?>
