<?php
include_once '../konfigurimi.php';

$specializimiResult = $conn->query("SELECT ID_Specializimi, Specializimi FROM specializimi");
$specializimiOptions = [];
if ($specializimiResult) {
    while ($row = $specializimiResult->fetch_assoc()) {
        $specializimiOptions[] = $row;
    }
}

$lokacioniResult = $conn->query("SELECT ID_Lokacioni, Lokacioni FROM lokacioni");
$lokacioniOptions = [];
if ($lokacioniResult) {
    while ($row = $lokacioniResult->fetch_assoc()) {
        $lokacioniOptions[] = $row;
    }
}

$kontaktiResult = $conn->query("SELECT ID_Kontakti, ID_Doktori FROM kontakti");
$kontaktiOptions = [];
if ($kontaktiResult) {
    while ($row = $kontaktiResult->fetch_assoc()) {
        $kontaktiOptions[] = $row;
    }
}

$successMessage = '';
$errorMessage = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['specializimi'])) {
        $idSpecializimi = $_POST['specializimi'];
    } else {
        $errorMessage = "Ju lutemi zgjidhni një specializim.";
    }

    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        if (empty($email)) {
            $errorMessage = "Email është i detyrueshëm.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "Ju lutemi jepni një email të vlefshëm.";
        }
    }

    if (isset($_POST['numriLicences'])) {
        $numriLicences = trim($_POST['numriLicences']);
        if (empty($numriLicences) || !preg_match("/^KO/", $numriLicences)) {
            $errorMessage = "Numri i Licencës duhet të fillojë me 'KO'.";
        }
    } else {
        $errorMessage = "Numri i Licencës është i detyrueshëm.";
    }

    $emriMbiemri = trim($_POST['emriMbiemri']);
    if (empty($emriMbiemri)) {
        $errorMessage = "Ju lutemi shtoni emrin dhe mbiemrin e doktorit.";
    }

    if (isset($_POST['lokacioni'])) {
        $idLokacioni = $_POST['lokacioni'];
    } else {
        $errorMessage = "Ju lutemi zgjidhni një lokacion.";
    }

    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']);
    }

    if (isset($_POST['fjalekalimi'])) {
        $password = trim($_POST['fjalekalimi']);
        if (empty($password)) {
            $errorMessage = "Fjalëkalimi është i detyrueshëm.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        }
    }

    if (empty($errorMessage)) {
        $stmt = $conn->prepare("INSERT INTO doktoret (EmriMbiemri, ID_Specializimi, ID_Lokacioni, Foto, NumriLicences, Fjalekalimi, Email) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("siissss", $emriMbiemri, $idSpecializimi, $idLokacioni, $foto, $numriLicences, $hashedPassword, $email);

            if ($stmt->execute()) {
                $successMessage = 'Doktori u shtua me sukses!';
            } else {
                $errorMessage = 'Ka ndodhur një gabim: ' . htmlspecialchars($stmt->error);
            }

            $stmt->close();
        } else {
            $errorMessage = 'Ka ndodhur një gabim: ' . htmlspecialchars($conn->error);
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/FaqjaadminCSS/doktoret.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>E-Mjeku - Shto Doktor</title>
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
    <h2>Shto Doktor të Ri</h2>

    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
        <script>
            setTimeout(function() {
                window.location.href = "doktoret.php";
            }, 3000);
        </script>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="numriLicences" class="form-label">Numri i Licencës</label>
            <input type="text" name="numriLicences" id="numriLicences" class="form-control" required pattern="KO.*">
        </div>
        <div class="mb-3">
            <label for="emriMbiemri" class="form-label">Emri dhe Mbiemri</label>
            <input type="text" name="emriMbiemri" id="emriMbiemri" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="fjalekalimi" class="form-label">Fjalëkalimi</label>
            <input type="password" name="fjalekalimi" id="fjalekalimi" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="specializimi" class="form-label">Specializimi</label>
            <select name="specializimi" id="specializimi" class="form-select" required>
                <option value="">Zgjidh Specializimin</option>
                <?php foreach ($specializimiOptions as $specializimi): ?>
                    <option value="<?php echo htmlspecialchars($specializimi['ID_Specializimi']); ?>"><?php echo htmlspecialchars($specializimi['Specializimi']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="lokacioni" class="form-label">Lokacioni</label>
            <select name="lokacioni" id="lokacioni" class="form-select" required>
                <option value="">Zgjidh Adresen</option>
                <?php foreach ($lokacioniOptions as $lokacioni): ?>
                    <option value="<?php echo htmlspecialchars($lokacioni['ID_Lokacioni']); ?>"><?php echo htmlspecialchars($lokacioni['Lokacioni']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="foto" class="form-label">Zgjidh Foton</label>
            <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Shto Doktorin</button>
        <a href="doktoret.php" class="btn btn-secondary">Kthehu</a>
    </form>
</div>
<?php include_once '../footer.php'; ?>
</body>
</html>
