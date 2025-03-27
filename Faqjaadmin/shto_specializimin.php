<?php

include_once '../konfigurimi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $specializimi = trim($_POST['specializimi']);

    if (!empty($specializimi)) {
        $stmt = $conn->prepare("INSERT INTO specializimi (Specializimi) VALUES (?)");
        if (!$stmt) {
            $_SESSION['error_message'] = 'Gabim gjatë përgatitjes së pyetjes: ' . $conn->error;
            header("Location: specializimi.php");
            exit;
        }

        $stmt->bind_param("s", $specializimi);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Specializimi u shtua me sukses!';
            $specializimi = '';
            header("Location: specializimi.php"); 
            exit;
        } else {
            $_SESSION['error_message'] = 'Gabim gjatë shtimit të specializimit: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error_message'] = 'Ju lutem plotësoni të gjitha fushat.';
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
    <title>E-Mjeku - Shto Specializim</title>
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
        <br>
        <h2>Shto Specializim të Ri</h2>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error_message']); ?></div>
            <?php unset($_SESSION['error_message']);  ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success" id="successMessage"><?php echo htmlspecialchars($_SESSION['success_message']); ?></div>
            <script>
                setTimeout(function() {
                    window.location.href = "specializimi.php"; 
                }, 3000);
            </script>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <form action="shto_specializimin.php" method="POST">
            <div class="mb-3">
                <label for="specializimi" class="form-label">Emri i Specializimit</label>
                <input type="text" class="form-control" id="specializimi" name="specializimi" required>
            </div>
            <button type="submit" class="btn btn-primary">Shto Specializimin</button>
            <a href="specializimi.php" class="btn btn-secondary">Kthehu</a>
        </form>
    </div>

    <?php
    $conn->close();
    ?>
    <?php include_once '../footer.php'; ?>
</body>
</html>
