<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['ID_Perdoruesi'])) {
    echo "Gabim: Nuk keni hyrë si përdorues.";
    exit();
}

// Database connection
$host = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "smd";

$conn = new mysqli($host, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Gabim në lidhje me bazën e të dhënave: " . $conn->connect_error);
}

// Fetch the list of doctors the user has communicated with
$userId = $_SESSION['ID_Perdoruesi'];
$sql = "SELECT DISTINCT d.ID_Doktori, d.EmriMbiemri FROM doktoret d
        JOIN mesazhet_chat m ON (m.id_msg_hyrse = ? OR m.id_msg_dalse = ?)
        WHERE d.ID_Doktori = m.id_msg_hyrse OR d.ID_Doktori = m.id_msg_dalse";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userId, $userId);
$stmt->execute();
$doctorResult = $stmt->get_result();
$doctors = [];
while ($row = $doctorResult->fetch_assoc()) {
    $doctors[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat me Doktorin</title>
    <link rel="stylesheet" href="./css/chat.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* Add your chat interface styles here */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
        }
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #f0f4f8;
            padding: 0 10px;
        }
        .wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 800px;
            background: #B3E5FC;
            border-radius: 8px;
            box-shadow: 0 0 128px 0 rgba(0,0,0,0.1), 0 32px 64px -48px rgba(0,0,0,0.5);
            padding: 20px;
            position: relative; /* Allow for positioning the link */
        }
        /* Ballina link positioned in the top-left corner */
        .ballina-link {
            position: absolute;
            top: 20px;
            left: 20px;
            text-decoration: none;
            color: #333;
            font-size: 18px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }
        .ballina-link i {
            margin-right: 5px;
        }
        .doctor-list {
            width: 100%;
            padding: 20px;
            border-bottom: 1px solid #ddd;
            overflow-y: auto;
        }
        .doctor-list h4 {
            margin-top: 10px;
            margin-bottom: 20px;
            text-align: center;
            background-color: white; /* White background */
            color: #333; /* Black text color */
            padding: 8px; /* Padding for spacing */
            border-radius: 5px; /* Rounded corners */
            font-size: 22px; /* Font size */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Optional: subtle shadow for more depth */
        }

        .doctor-list ul {
            list-style: none;
            padding: 0;
        }
        .doctor-list ul li {
            margin-bottom: 10px;
            text-align: center;
        }
        .doctor-list ul li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            cursor: pointer;
            font-size: 18px;
        }
        .doctor-list ul li a:hover {
            text-decoration: underline;
        }
        .home-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            font-size: 16px;
            width: 100%;
        }
        .home-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <a href="../ballina.php" class="ballina-link"><i class='bx bxs-left-arrow-alt'></i> Ballina</a>
        <div class="doctor-list">
            <h4>Lista e Doktorëve që keni kontaktuar!</h4>
            <ul id="doctor-list">
                <?php foreach ($doctors as $doctor): ?>
                    <li>
                        <a href="chat.php?doctor_id=<?php echo $doctor['ID_Doktori']; ?>">
                            <?php echo htmlspecialchars($doctor['EmriMbiemri']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>
