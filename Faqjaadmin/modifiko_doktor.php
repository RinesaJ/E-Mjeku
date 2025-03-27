<?php
include_once '../konfigurimi.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: doktoret.php");
    exit();
}

$ID_Doktori = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM doktoret WHERE ID_Doktori = ?");
if ($stmt === false) {
    die('Prepare failed: ' . $conn->error); 
}
$stmt->bind_param("i", $ID_Doktori);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: doktoret.php");
    exit();
}

$doktor = $result->fetch_assoc();
$stmt->close();

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ID_Doktori = intval($_POST['ID_Doktori']);
    $emriMbiemri = $_POST['emriMbiemri'];
    $email = $_POST['email'];
    $idSpecializimi = intval($_POST['specializimi']);
    $idLokacioni = intval($_POST['lokacioni']);
    $foto = null;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']);
    } else {

        $stmtFoto = $conn->prepare("SELECT Foto FROM doktoret WHERE ID_Doktori = ?");
        if ($stmtFoto === false) {
            die('Prepare failed: ' . $conn->error);
        }
        $stmtFoto->bind_param("i", $ID_Doktori);
        $stmtFoto->execute();
        $stmtFoto->bind_result($existingFoto);
        $stmtFoto->fetch();
        $foto = $existingFoto;
        $stmtFoto->close();
    }

    $stmt = $conn->prepare("UPDATE doktoret SET EmriMbiemri = ?, Email = ?, ID_Specializimi = ?, ID_Lokacioni = ?, Foto = ? WHERE ID_Doktori = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param("ssiibi", $emriMbiemri, $email, $idSpecializimi, $idLokacioni, $foto, $ID_Doktori);

    if ($stmt->execute()) {
        $successMessage = 'Doktori u përditësua me sukses!';
    } else {
        $errorMessage = 'Ka ndodhur një gabim: ' . $stmt->error;
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/FaqjaadminCSS/doktoret.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>E-Mjeku - Modifiko Doktor</title>
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
    <h2>Modifiko Doktor</h2>
    
    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success" id="successMessage"><?php echo htmlspecialchars($successMessage); ?></div>
        <script>
            setTimeout(function() {
                window.location.href = "doktoret.php";
            }, 3000);
        </script>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="ID_Doktori" value="<?php echo htmlspecialchars($doktor['ID_Doktori']); ?>">

    <div class="mb-3">
        <label for="emriMbiemri" class="form-label">Emri dhe Mbiemri</label>
        <input type="text" name="emriMbiemri" id="emriMbiemri" class="form-control" value="<?php echo htmlspecialchars($doktor['EmriMbiemri']); ?>" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($doktor['Email']); ?>" required>
    </div>

    <div class="mb-3">
        <label for="specializimi" class="form-label">Specializimi</label>
        <select name="specializimi" id="specializimi" class="form-select" required>
            <option value="">Zgjidh Specializimin</option>
            <?php foreach ($specializimiOptions as $specializimi): ?>
                <option value="<?php echo htmlspecialchars($specializimi['ID_Specializimi']); ?>" <?php if ($specializimi['ID_Specializimi'] == $doktor['ID_Specializimi']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($specializimi['Specializimi']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="lokacioni" class="form-label">Lokacioni</label>
        <select name="lokacioni" id="lokacioni" class="form-select" required>
            <option value="">Zgjidh Lokacionin</option>
            <?php foreach ($lokacioniOptions as $lokacioni): ?>
                <option value="<?php echo htmlspecialchars($lokacioni['ID_Lokacioni']); ?>" <?php if ($lokacioni['ID_Lokacioni'] == $doktor['ID_Lokacioni']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($lokacioni['Lokacioni']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="foto" class="form-label">Foto e Doktorit</label>
        <input type="file" name="foto" id="foto" class="form-control">
        <?php if ($doktor['Foto']): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($doktor['Foto']); ?>" alt="Doktori Foto" style="max-width: 150px; margin-top: 10px;">
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Përditëso</button>
    </form>
</div>
<?php include_once '../footer.php'; ?>
</body>
</html>
