<?php
include_once '../konfigurimi.php';

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($name) && !empty($email) && !empty($password)) {
        $stmt = $conn->prepare("INSERT INTO perdoruesit (EmriMbiemri, Email, Fjalekalimi) VALUES (?, ?, ?)");

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Përdoruesi u shtua me sukses!';
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $errorMessage = 'Ka ndodhur një gabim: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        $errorMessage = 'Të gjitha fushat janë të detyrueshme.';
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
    <title>E-Mjeku - Shto Përdorues</title>
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
    <h2>Shto Përdorues të Ri</h2>

    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success" id="successMessage"><?php echo htmlspecialchars($_SESSION['success_message']); ?></div>
        <script>
            setTimeout(function() {
                window.location.href = "perdoruesit.php"; 
            }, 3000);
        </script>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Emri</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Fjalëkalimi</label>
            <input type="text" class="form-control" id="password" name="password" required> <!-- Changed to text -->
        </div>
        <button type="submit" class="btn btn-primary">Shto Përdorues</button>
        <a href="perdoruesit.php" class="btn btn-secondary">Kthehu </a>
    </form>
</div>

<?php
$conn->close();
 include_once '../footer.php';
?>
</body>
</html>
