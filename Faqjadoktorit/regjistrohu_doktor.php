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
    $numriLicences = $_POST["numri_licences"];
    $username = $_POST["username"];
    $email = $_POST["email"];  
    $password = $_POST["password"]; 
    $specialization = $_POST["specialization"];
    $location = $_POST["location"];

    if (substr($numriLicences, 0, 2) !== "KO") {
        echo "Numri i Licencës duhet të fillojë me 'KO'.";
    } else {

        $checkUser = "SELECT * FROM doktoret WHERE NumriLicences=?";
        $stmtCheck = $data->prepare($checkUser);
        $stmtCheck->bind_param("s", $numriLicences);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        if ($resultCheck && $resultCheck->num_rows > 0) {
            echo "Doktori ekziston me këtë Numër Licencë!";
        } else {
            $sql = "INSERT INTO doktoret (NumriLicences, EmriMbiemri, Fjalekalimi, ID_Specializimi, ID_Lokacioni) 
                    VALUES (?, ?, ?, ?, ?)";

            $stmtInsert = $data->prepare($sql);
            if ($stmtInsert) {
                $stmtInsert->bind_param("ssiii", $numriLicences, $username, $password, $specialization, $location);

                try {
                    if ($stmtInsert->execute()) {
                        echo "Doktori u regjistrua me sukses!";
                        header("Location: profili.php");
                        exit; 
                    } else {
                        echo "Gabim në regjistrim!";
                    }
                } catch (mysqli_sql_exception $e) {
                    echo "Gabim: " . $e->getMessage();
                }
            } else {
                echo "Gabim në përgatitjen e deklaratës SQL!";
            }
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
    <link rel="stylesheet" href="../css/Faqjadoktori/ballina.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Regjistrohu si Doktor</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Moderustic:wght@300..800&display=swap');
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Moderustic", sans-serif;
    }
        .container{
            margin-left: 100px;
        }
        .wrapper {
            width: 80vh;
            background: transparent;
            border: 2px solid rgba(37, 139, 187, 0.244);
            backdrop-filter: blur(20px);
            color: #000;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(47, 114, 239, 0.278);
            padding: 8px 40px;

        }

        .wrapper h1 {
            margin-bottom: 15px;
            font-size: 36px;
            text-align: center;
        }

        .input-b {
            position: relative;
            width: 100%;
            height: 50px;
            margin: 3px 0;
        }

        .input-b input {
            width: 100%;
            height: 100%;
            background: transparent;
            outline: none;
            border: 2px solid #03ACF2;
            border-radius: 30px;
            padding-left: 30px;
            font-size: 16px;
        }

        .input-b input::placeholder {
            color: #000;
        }

        .input-b i {
            position: absolute;
            padding-left: 10px;
            padding-top: 16px;
        }

        .input-b select {
            width: 100%;
            height: 50px;
            background: transparent;
            outline: none;
            border: 2px solid #03ACF2;
            border-radius: 30px;
            padding-left: 30px;
            font-size: 16px;
            appearance: none; 
            background-color: #fff; 
        }

        .wrapper .kycu input {
            width: 100%;
            height: 45px;
            background: #fff;
            border: none;
            outline: none;
            border-radius: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .1);
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
        }

        .wrapper .link-regjistrim {
            font-size: 14.5px;
            text-align: center;
            margin: 20px 0 15px;
        }

        .link-regjistrim p a {
            color: #000;
            text-decoration: none;
            font-weight: 600;
        }

        .link-regjistrim p a:hover {
            text-decoration: underline;
            font-weight: bold;
        }

        .admin-info p {
            font-size: 12px;
            color: #555;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
<div class="wrapper">
    <h1>Regjistrohu si Doktor</h1>
    <form action="regjistrohu_doktor.php" method="POST">
        <!-- Numri Licencës -->
        <div class="input-b">
            <i class='bx bxs-user'></i>
            <input type="text" name="numri_licences" placeholder="Numri Licencës (fillon me KO)" required pattern="^KO.*" title="Numri i Licencës duhet të fillojë me 'KO'">
        </div>
        <br>

        <!-- Emri dhe Mbiemri -->
        <div class="input-b">
            <i class='bx bxs-user'></i>
            <input type="text" name="username" placeholder="Emri dhe Mbiemri" required>
        </div>
        <br>

        <!-- Email -->
        <div class="input-b">
            <i class='bx bxs-envelope'></i>
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <br>

        <!-- Password -->
        <div class="input-b">
            <i class='bx bxs-lock-alt'></i>
            <input type="password" name="password" placeholder="Fjalëkalimi" required>
        </div>
        <br>
<!-- Ngarko Foto -->
<p style="  font-size: 12px; color: red;">*Foto duhet të jetë max 64KB</p>
<div class="input-b" style="  height: 50px; border: 2px solid #03ACF2; border-radius: 30px; padding: 10px 15px;">
        <i class='bx bxs-image' style="font-size: 20px; position: absolute; left: 1px; bottom: 12px;"></i>
    <input type="file" name="foto" accept="image/*" required style="cursor: pointer; border: none; background: transparent; font-size: 16px;">
</div><br>
        <!-- Specializimi -->
        <div class="input-b">
            <i class='bx bxs-brain'></i>
            <select name="specialization" required>
                <option value="">Zgjidhni Specializimin</option>
                <?php
                $specializimiQuery = $data->query("SELECT ID_Specializimi, Specializimi FROM specializimi");
                while ($row = $specializimiQuery->fetch_assoc()) {
                    echo "<option value='{$row['ID_Specializimi']}'>{$row['Specializimi']}</option>";
                }
                ?>
            </select>
        </div>
        <br>

        <!-- Lokacioni -->
        <div class="input-b">
            <i class='bx bxs-location-plus'></i>
            <select name="location" required>
                <option value="">Zgjidhni Lokacionin</option>
                <?php
                $lokacioniQuery = $data->query("SELECT ID_Lokacioni, Lokacioni FROM lokacioni");
                while ($row = $lokacioniQuery->fetch_assoc()) {
                    echo "<option value='{$row['ID_Lokacioni']}'>{$row['Lokacioni']}</option>";
                }
                ?>
            </select>
        </div>
        <br>
        
        <!-- Submit butoni -->
        <div class="kycu">
            <input type="submit" value="Regjistrohu">
        </div>

        <!-- Kycu Link -->
        <div class="link-regjistrim">
            <p>Keni një account? <a href="../kycu.php">Kycu</a></p>
        </div>
    </form>
</div>
</div>
</body>
</html>
