<?php
include_once '../konfigurimi.php';

$successMessage = '';
$errorMessage = '';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = 'ID e specializimit nuk është e vlefshme.';
    header("Location: specializimi.php");
    exit;
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT Specializimi FROM specializimi WHERE ID_Specializimi = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($specializimi);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_specializimi = trim($_POST['specializimi']);

    if (!empty($new_specializimi)) {
        $stmt = $conn->prepare("UPDATE specializimi SET Specializimi = ? WHERE ID_Specializimi = ?");
        if (!$stmt) {
            $errorMessage = 'Gabim gjatë përgatitjes së pyetjes: ' . $conn->error;
        } else {
            $stmt->bind_param("si", $new_specializimi, $id);

            if ($stmt->execute()) {
                $successMessage = 'Specializimi u përditësua me sukses!';
            } else {
                $errorMessage = 'Gabim gjatë përditësimit të specializimit: ' . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        $errorMessage = 'Ju lutem plotësoni të gjitha fushat.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/FaqjaadminCSS/perdoruesit.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>E-Mjeku - Modifiko Specializim</title>
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
        <h2>Përditëso Specializimin</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success" id="successMessage"><?php echo htmlspecialchars($successMessage); ?></div>
            <script>
                setTimeout(function() {
                    window.location.href = "specializimi.php"; 
                }, 3000);
            </script>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="specializimi" class="form-label">Emri i Specializimit</label>
                <input type="text" class="form-control" id="specializimi" name="specializimi" value="<?php echo htmlspecialchars($specializimi); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Modifiko Specializimin</button>
            <a href="specializimi.php" class="btn btn-secondary">Kthehu</a>
        </form>
    </div><br><br><br><br><br><br><br><br><br>
    

    <?php
    $conn->close();
    include_once '../footer.php';
    ?>
</body>
</html>
