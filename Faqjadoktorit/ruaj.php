<?php
session_start();
include_once '../konfigurimi.php';


if (!isset($_SESSION['Perdoruesi'])) {
    header("Location: ../kycu.php"); 
    exit();
}


$perdoruesi = $_SESSION['Perdoruesi'];
$query = $conn->prepare("SELECT * FROM doktoret WHERE EmriMbiemri = ?");
$query->bind_param("s", $perdoruesi);
$query->execute();
$result = $query->get_result();
$doctor = $result->fetch_assoc();

if (!$doctor) {
    $_SESSION['error_message'] = "Doktori nuk u gjet!";
    header("Location: regjistrohu_doktor.php"); 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emri_mbiemri = $_POST['emri_mbiemri'];
    $id_specializimi = $_POST['id_specializimi'];
    $id_lokacioni = $_POST['id_lokacioni'];
    $facebook_url = $_POST['facebook_url'];
    $website_url = $_POST['website_url'];
    $linkedin_url = $_POST['likedin_url'];
    $instagram_url = $_POST['instagram_url'];

    $foto = $doctor['Foto']; 
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {

        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

        if (in_array($file_extension, $allowed_extensions)) {

            $upload_dir = '../foto/';
            $file_name = uniqid('profile_') . '.' . $file_extension;
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $file_path)) {
                $foto = file_get_contents($file_path); 
            } else {
                $_SESSION['error_message'] = 'Gabim gjatë ngarkimit të fotos!';
                header("Location: profili.php");
                exit();
            }
        } else {
            $_SESSION['error_message'] = 'Formati i fotos nuk është i vlefshëm!';
            header("Location: profili.php");
            exit();
        }
    }

    $updateQuery = $conn->prepare("UPDATE doktoret SET EmriMbiemri = ?, ID_Specializimi = ?, ID_Lokacioni = ?, Foto = ?, facebook_url = ?, website_url = ?, linkedin_url = ?, instagram_url = ? WHERE EmriMbiemri = ?");
    $updateQuery->bind_param("siissssss", $emri_mbiemri, $id_specializimi, $id_lokacioni, $foto, $facebook_url, $website_url, $linkedin_url, $instagram_url, $perdoruesi);

    if ($updateQuery->execute()) {
        $_SESSION['success_message'] = "Profili u përditësua me sukses!";
    } else {
        $_SESSION['error_message'] = "Gabim gjatë përditësimit të profilit: " . $updateQuery->error;
    }

    header("Location: profili.php");
    exit();
}
?>
