<?php
session_start(); // Start the session

$host = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "smd";

$data = mysqli_connect($host, $dbUser, $dbPassword, $dbName);
if ($data == false) {
    die("Gabim në lidhje!");
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Perdoruesi = isset($_POST["Perdoruesi"]) ? trim($_POST["Perdoruesi"]) : '';
    $Email = isset($_POST["Email"]) ? trim($_POST["Email"]) : '';
    $Fjalkalimi = isset($_POST["Fjalkalimi"]) ? trim($_POST["Fjalkalimi"]) : '';

    if (empty($Perdoruesi) || empty($Email) || empty($Fjalkalimi)) {
        $error_message = "Ju lutem plotësoni të gjitha fushat!";
    } else {
        // Admin login
        if ($Email === 'admin@demo.com') {
            $stmt = $data->prepare("SELECT * FROM perdoruesit WHERE Email=?");
            if (!$stmt) {
                die("Query prepare failed: " . $data->error);
            }

            $stmt->bind_param("s", $Email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();

                if ($Fjalkalimi === $row["Fjalekalimi"]) {
                    session_regenerate_id(true);
                    $_SESSION["ID_Perdoruesi"] = $row["ID_Perdoruesi"]; // Store user ID
                    $_SESSION["Perdoruesi"] = $Perdoruesi;
                    $_SESSION["Email"] = $Email;
                    $redirectTo = isset($_SESSION['redirect_to']) ? $_SESSION['redirect_to'] : 'Faqjaadmin/faqjadmin.php';
                    unset($_SESSION['redirect_to']);
                    header("Location: $redirectTo");
                    exit;
                } else {
                    $error_message = "Fjalëkalimi është gabim!";
                }
            } else {
                $error_message = "Përdoruesi ose Email është gabim!";
            }
        }
        // Doctor login
        elseif (substr($Email, -3) === ".dr") {
            $stmt = $data->prepare("SELECT * FROM doktoret WHERE EmriMbiemri=? AND Email=?");
            if (!$stmt) {
                die("Query prepare failed: " . $data->error);
            }

            $stmt->bind_param("ss", $Perdoruesi, $Email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();

                if ($Fjalkalimi === $row["Fjalekalimi"]) {
                    session_regenerate_id(true);
                    $_SESSION["ID_Doktori"] = $row["ID_Doktori"]; // Store doctor ID
                    $_SESSION["Perdoruesi"] = $Perdoruesi;
                    $_SESSION["Email"] = $Email;
                    $redirectTo = isset($_SESSION['redirect_to']) ? $_SESSION['redirect_to'] : 'Faqjadoktorit/profili.php';
                    unset($_SESSION['redirect_to']);
                    header("Location: $redirectTo");
                    exit;
                } else {
                    $error_message = "Fjalëkalimi është gabim!";
                }
            } else {
                $error_message = "Përdoruesi ose Email është gabim!";
            }
        }
        // Regular user login
        else {
            $stmt = $data->prepare("SELECT * FROM perdoruesit WHERE EmriMbiemri=? AND Email=?");
            if (!$stmt) {
                die("Query prepare failed: " . $data->error);
            }

            $stmt->bind_param("ss", $Perdoruesi, $Email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();

                if ($Fjalkalimi === $row["Fjalekalimi"]) {
                    session_regenerate_id(true);
                    $_SESSION["ID_Perdoruesi"] = $row["ID_Perdoruesi"]; // Store user ID
                    $_SESSION["Perdoruesi"] = $Perdoruesi;
                    $_SESSION["Email"] = $Email;
                    $redirectTo = isset($_SESSION['redirect_to']) ? $_SESSION['redirect_to'] : 'Faqjauser/ballina.php';
                    unset($_SESSION['redirect_to']);
                    header("Location: $redirectTo");
                    exit;
                } else {
                    $error_message = "Fjalëkalimi është gabim!";
                }
            } else {
                $error_message = "Përdoruesi ose Email është gabim!";
            }
        }

        $stmt->close();
    }
}

$data->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/kycu.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }
    </style>
    <title>Sistemi per Menaxhimin e Doktoreve(SMD)</title>
</head>
<body>
    <div class="wrapper">
        <h1>Kyçu</h1>
        <form action="kycu.php" method="POST">
            <?php if (!empty($error_message)) { ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php } ?>
            <div class="input-b">
                <i class='bx bxs-user'></i>
                <input type="text" name="Perdoruesi" placeholder="Përdoruesi" required>
            </div>
            <br><br>
            <div class="input-b">
                <i class='bx bxs-envelope'></i>
                <input type="email" name="Email" placeholder="Email" required>
            </div>
            <br><br>
            <div class="input-b">
                <i class='bx bxs-lock-alt'></i>
                <input type="password" name="Fjalkalimi" placeholder="Fjalëkalimi" required>
            </div>
            <br><br>
            <div class="kycu">
                <input type="submit" value="Kyçu">
            </div>
            
            <div class="link-regjistrim">
                <p>Nuk keni një account? <a href="./regjistrohu.php">Regjistrohu</a></p>
            </div>
            <div class="link-regjistrim">
                <p>Jeni doktor nuk keni një account? <a href="./Faqjadoktorit/regjistrohu_doktor.php">Regjistrohu si Dokotor</a></p>
            </div>
        </form>
    </div>
</body>
</html>
