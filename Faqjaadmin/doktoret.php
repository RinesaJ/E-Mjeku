<?php
include_once '../konfigurimi.php';


if (isset($_SESSION['success_message'])) {
    echo "<div class='alert alert-success' id='successMessage'>" . htmlspecialchars($_SESSION['success_message']) . "</div>";
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo "<div class='alert alert-danger' id='errorMessage'>" . htmlspecialchars($_SESSION['error_message']) . "</div>";
    unset($_SESSION['error_message']);
}

$query = "
    SELECT 
        d.ID_Doktori,
        d.EmriMbiemri,
        d.Email, -- Select Email directly from the doktoret table
        s.Specializimi,
        l.Lokacioni
    FROM 
        doktoret d
    LEFT JOIN 
        specializimi s ON d.ID_Specializimi = s.ID_Specializimi
    LEFT JOIN 
        lokacioni l ON d.ID_Lokacioni = l.ID_Lokacioni
";


$result = $conn->query($query);

if ($result === false) {
    die("Gabim në marrjen e doktorëve: " . $conn->error);
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
    <title>E-Mjeku - Doktorët</title>
    <style>
        .alert {
            display: none;
        }
    </style>
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
    <br><br>
    <h2>Menaxhimi i Doktorëve</h2>
    <div class="button-container">
        <a href="shto_doktorin.php" class="btn btn-primary">Shto Doktor të Ri</a>
    </div>
    <br>
    <h3>Informacionet e Doktorëve</h3>
    
    <table class="table">
    <thead>
        <tr>
            <th class="id">ID</th>
            <th>Emri dhe Mbiemri</th>
            <th>Specializimi</th>
            <th>Lokacioni</th>
            <th>Email</th>
            <th class="veprime">Veprime</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['ID_Doktori']); ?></td>
                    <td><?php echo htmlspecialchars($row['EmriMbiemri']); ?></td>
                    <td><?php echo htmlspecialchars($row['Specializimi']); ?></td>
                    <td><?php echo htmlspecialchars($row['Lokacioni']); ?></td>
                    <td><?php echo htmlspecialchars($row['Email']); ?></td> 
                    <td>
                        <a href="modifiko_doktor.php?id=<?php echo htmlspecialchars($row['ID_Doktori']); ?>" class="btn btn-secondary">Modifiko</a>
                        <a href="fshije_doktor.php?id=<?php echo htmlspecialchars($row['ID_Doktori']); ?>" class="btn btn-danger" onclick="return confirm('A jeni të sigurt se dëshironi të fshini këtë doktor?');">Fshi</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">Nuk ka asnjë doktor të regjistruar.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>

<?php
$conn->close();
?>
<script>
    const menuToggle = document.getElementById("menu-toggle");
    const navMenu = document.getElementById("nav-menu");

    menuToggle.addEventListener("click", () => {
        navMenu.classList.toggle("active");
    });
</script><br><br>
<?php include_once '../footer.php'; ?>
</body>
</html>
