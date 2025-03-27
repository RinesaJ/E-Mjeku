<?php
$host = "localhost";
$dbUser = "root";
$dbPassword = ""; 
$dbName = "smd";

session_start();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$data = mysqli_connect($host, $dbUser, $dbPassword, $dbName);
if ($data == false) {
    die("Gabim në lidhje!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"]; 

    $checkUser = "SELECT * FROM perdoruesit WHERE EmriMbiemri=?";
    $stmtCheck = $data->prepare($checkUser);
    $stmtCheck->bind_param("s", $username);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck && $resultCheck->num_rows > 0) {
        echo "Përdoruesi ekziston!";
    } else {

        $sql = "INSERT INTO perdoruesit (EmriMbiemri, Email, Fjalekalimi) VALUES (?, ?, ?)";
        $stmtInsert = $data->prepare($sql);
        $stmtInsert->bind_param("sss", $username, $email, $password);

        try {
            if ($stmtInsert->execute()) {
                echo "Regjistrimi u krye me sukses!";
                header("Location: kycu.php");
                exit; 
            } else {
                echo "Gabim në regjistrim!";
            }
        } catch (mysqli_sql_exception $e) {
            echo "Gabim: " . $e->getMessage();
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/kycu.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Regjistrohu</title>
</head>
<body>
<div class="wrapper">
    <h1>Regjistrohu</h1>
    <form action="regjistrohu.php" method="POST">
        <div class="input-b">
            <i class='bx bxs-user'></i>
            <input type="text" name="username" placeholder="Perdoruesi" required>
        </div>
        <br>
        <div class="input-b">
            <i class='bx bxs-envelope'></i>
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <br>
        <div class="input-b">
            <i class='bx bxs-lock-alt'></i>
            <input type="password" name="password" placeholder="Fjalkalimi" required>
        </div>
        <br>
        <br>
        <div class="kycu">
            <input type="submit" value="Regjistrohu">
        </div>
        <div class="link-regjistrim">
            <p>Keni nje account? <a href="./kycu.php">Kycu</a></p>
        </div>
    </form>
</div>
</body>
</html>
