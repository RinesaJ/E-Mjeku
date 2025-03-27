<?php
include_once '../konfigurimi.php';

if (!isset($_SESSION["Perdoruesi"])) {
    header("Location: ../kycu.php");
    exit;
}

$result = $conn->query("SELECT ID_Mesazhi, Emri, Email, Mesazhi, DataDergimit, Statusi FROM mesazhe");

if ($result === false) {
    die("Gabim në marrjen e mesazheve: " . $conn->error);
}

$successMessage = $_SESSION['success_message'] ?? '';
$errorMessage = $_SESSION['error_message'] ?? '';

unset($_SESSION['success_message'], $_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/FaqjaadminCSS/kontakti.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>E-Mjeku - Kontakti</title>
</head>
<body>
    <header>
    <div class="logo">
        <a href="faqjadmin.php">
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
                        <?php if (!empty($_SESSION["Perdoruesi"])): ?>
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
        <h2 style="margin-left: 180px;">Menaxhimi i Mesazheve të Përdoruesve</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
            <script>
                setTimeout(() => window.location.href = "kontakti.php", 3000);
            </script>
        <?php endif; ?>

        <h3 style="margin-left: 300px;">Mesazhet e Dërguara</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Emri</th>
                    <th>Email</th>
                    <th>Mesazhi</th>
                    <th>Data Dërgimit</th>
                    <th>Statusi</th>
                    <th>Veprime</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['ID_Mesazhi']); ?></td>
                            <td><?php echo htmlspecialchars($row['Emri']); ?></td>
                            <td><?php echo htmlspecialchars($row['Email']); ?></td>
                            <td><?php echo htmlspecialchars($row['Mesazhi']); ?></td>
                            <td><?php echo htmlspecialchars($row['DataDergimit']); ?></td>
                            <td><?php echo htmlspecialchars($row['Statusi']); ?></td>
                            <td>
                            <td>
                            <a href="mailto:<?php echo htmlspecialchars($row['Email']); ?>?subject=Pergjigjja%20e%20Mesazhit%20tuaj&body=I%2FE Nderuar%20<?php echo htmlspecialchars($row['Emri']); ?>%2C%0A%0AJa%20pergjigja%20juaj%20ne%20mesazhin%20tuaj.%0A%0AGjitha%20te%20mirat%2C%0ASitemi%20Menaxhimin%20e%20Doktoreve%20%28SMD%29" class="btn btn-secondary">Pergjigju</a>
                            <a href="fshije_kontakt.php?id=<?php echo htmlspecialchars($row['ID_Mesazhi']); ?>" class="btn btn-danger" onclick="return confirm('A jeni të sigurt se dëshironi të fshini këtë mesazh?');">Fshi</a>
                        </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Nuk ka mesazhe të dërguara.</td>
                    </tr>
                <?php endif; ?>
                
            </tbody>
        </table>
    </div>

    <?php $conn->close(); ?>
    <script>
    const menuToggle = document.getElementById("menu-toggle");
    const navMenu = document.getElementById("nav-menu");

    menuToggle.addEventListener("click", () => {
        navMenu.classList.toggle("active");
    });
</script><br><br><br>
<?php include_once '../footer.php'; ?>
</body>
</html>
