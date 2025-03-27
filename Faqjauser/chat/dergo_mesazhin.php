<?php
session_start();

// Verifikimi nëse përdoruesi është i kyçur
if (!isset($_SESSION['ID_Perdoruesi'])) {
    header("Location: ../kycu.php"); // Rrjedhja për në faqen e kyçjes nëse nuk është kyçur
    exit();
}

// Lidhja me bazën e të dhënave
$host = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "smd";

$conn = new mysqli($host, $dbUser, $dbPassword, $dbName);

// Verifikimi i gabimeve të lidhjes
if ($conn->connect_error) {
    die("Gabim në lidhje me bazën e të dhënave: " . $conn->connect_error);
}

// Marrja e të dhënave nga formulari
$id_msg_hyrse = $_POST['id_msg_hyrse']; // ID e përdoruesit (derguesi)
$id_msg_dalse = $_POST['id_msg_dalse']; // ID i doktorit (pranuesi)
$msg = $_POST['msg']; // Mesazhi
$tipi_derguesit = $_POST['tipi_derguesit']; // Lloji i derguesit (Perdoruesi)
$ID_Perdoruesi = $_POST['ID_Perdoruesi']; // ID e përdoruesit
$ID_Doktori = $_POST['ID_Doktori']; // ID e doktorit

// Pastrimi i mesazhit për të parandaluar SQL injection
$msg = $conn->real_escape_string($msg);

// Kontrollo që mesazhi nuk është bosh
if (empty($msg)) {
    die("Mesazhi është bosh!");
}

// Insertimi i mesazhit në databazë
$sql = "INSERT INTO mesazhet_chat (id_msg_hyrse, id_msg_dalse, msg, tipi_derguesit, koha) 
        VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $id_msg_hyrse, $id_msg_dalse, $msg, $tipi_derguesit);

// Kontrollo nëse insertimi ka pasur sukses
if ($stmt->execute()) {
    echo "Mesazhi u dërgua me sukses!";
} else {
    echo "Gabim gjatë dërgimit të mesazhit!";
}

$stmt->close();
$conn->close();
?>
