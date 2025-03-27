<?php
include_once '../konfigurimi.php';

$email = $telefon = '';
$doctorID = ''; 
$errors = [];

$doctors = [];
$result = $conn->query("SELECT ID_Doktori, EmriMbiemri_pdd FROM doktoret");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctorID = $_POST['emri']; 
    $email = trim($_POST['email']);
    $telefon = trim($_POST['telefon']);
    
    if (empty($doctorID)) {
        $errors[] = 'Doktori është i nevojshëm.';
    }
    if (empty($email)) {
        $errors[] = 'Emaili është i nevojshëm.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Emaili duhet të jetë i valid.';
    }
    if (empty($telefon)) {
        $errors[] = 'Numri i telefonit është i nevojshëm.';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT EmriMbiemri_pdd FROM doktoret WHERE ID_Doktori = ?");
        $stmt->bind_param("i", $doctorID);
        $stmt->execute();
        $stmt->bind_result($doctorName);
        $stmt->fetch();
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO Kontakti (Emri, Email, Telefon, ID_Doktori) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $doctorName, $email, $telefon, $doctorID);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Kontakt u shtua me sukses!';
            header("Location: kontakti.php");
            exit();
        } else {
            $_SESSION['error_message'] = 'Gabim gjatë shtimit të kontaktit: ' . $conn->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error_message'] = implode('<br>', $errors); 
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/ModuliadministratoritCSS/kontakti.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>E-Mjeku - Shto Kontakt</title>
</head>
<body>
<header>
<div class="logo">
        <a href="ballina.php">
            <img src="../foto/SMD.png" style="width: 100px; margin-top: 10px;" alt="Logo">
        </a>
    </div>
    <nav>
        <div class="nav-container">
            <button class="menu-toggle" id="menu-toggle">&#9776;</button>
            <ul id="nav-menu" class="nav-menu">
                <li><a href="faqjadmin.php">Ballina</a></li>
                <li><a href="./perdoruesit.php">Përdoruesit</a></li>
                <li><a href="./doktoret.php">Doktoret</a></li>
                <li><a href="./specializimi.php">Specializimi</a></li>
                <li><a href="./kontakti.php">Kontakti</a></li>
                <li>
                    <?php if (isset($_SESSION["Perdoruesi"]) && !empty($_SESSION["Perdoruesi"])): ?>
                        <a href="../ckycu.php"><i class='bx bx-log-out'></i> <?php echo htmlspecialchars($_SESSION["Perdoruesi"]); ?></a>
                    <?php else: ?>
                        <a href="../kycu.php"><i class='bx bx-log-in'></i> Kycu</a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </nav>
</header>

<div class="wrapper">
    <h2>Shto Kontakt të Ri</h2>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div id="message" class="alert alert-success">
            <?php echo htmlspecialchars($_SESSION['success_message']); ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div id="message" class="alert alert-danger">
            <?php echo htmlspecialchars($_SESSION['error_message']); ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <script>
        setTimeout(function() {
            var message = document.getElementById('message');
            if (message) {
                message.style.display = 'none';
            }
        }, 3000); 
    </script>

    <form action="shto_kontakt.php" method="post">
        <div class="mb-3">
            <label for="emri" class="form-label">Doktori</label>
            <select class="form-control" id="emri" name="emri" required>
                <option value="">Zgjidh Doktorin</option>
                <?php foreach ($doctors as $doctor): ?>
                    <option value="<?php echo $doctor['ID_Doktori']; ?>" <?php echo ($doctorID == $doctor['ID_Doktori']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($doctor['EmriMbiemri_pdd']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="mb-3">
            <label for="telefon" class="form-label">Telefon</label>
            <input type="text" class="form-control" id="telefon" name="telefon" value="<?php echo htmlspecialchars($telefon); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Shto Kontakt</button>
        <a href="kontakti.php" class="btn btn-secondary">Kthehu</a>
    </form>
</div>
<?php include_once '../footer.php'; ?>
</body>
</html>
