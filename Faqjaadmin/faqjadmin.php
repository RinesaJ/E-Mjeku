<?php 
session_start();
if (!isset($_SESSION["Perdoruesi"])) {
    header("location: ../kycu.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/FaqjaadminCSS/faqjadmin.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>E-Mjeku - Faqja Administratorit</title>
</head>
<body>
<header> 
<div class="logo">
        <a href="../Faqjauser/ballina.php">
            <img src="../foto/SMD.png" style="width: 100px; margin-top: 10px;" alt="Logo">
        </a>
    </div>
    <nav>
        <div class="nav-container">
            <button class="menu-toggle" id="menu-toggle">&#9776;</button>
            <ul id="nav-menu" class="nav-menu">
                <li><a href="faqjadmin.php">Ballina</a></li>
                <li><a href="./perdoruesit.php">Përdoruesit</a></li>
                <li><a href="./doktoret.php">Doktorët</a></li>
                <li><a href="./specializimi.php">Specializimi</a></li>
                <li><a href="./kontakti.php">Kontakti</a></li>
                <li>
                    <?php if (isset($_SESSION["Perdoruesi"]) && !empty($_SESSION["Perdoruesi"])): ?>
                        <a href="../ckycu.php"><i class='bx bx-log-out'></i> <?php echo htmlspecialchars($_SESSION["Perdoruesi"]); ?></a>
                    <?php else: ?>
                        <a href="../kycu.php"><i class='bx bx-log-in'></i> Kyçu</a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </nav>
</header>
<div class="fadmin">
    <h2>Faqja Administratorit</h2>
    <p>Kjo faqe mundëson menaxhimin e të dhënave në platformë (Moduli Përdoruesit & Moduli Administratorit).</p>
</div>
<div class="card-container">
    <div class="card">
        <h3><i class='bx bxs-user-detail'></i> Menaxho Përdorues</h3>
        <p>Menaxhoni përdoruesit në modulin e përdoruesit dhe administratorit.</p>
        <a href="./perdoruesit.php" class="btn">Menaxho</a>
    </div>
    <div class="card">
        <h3><i class='bx bxs-user-detail'></i> Menaxho Doktorë</h3>
        <p>Menaxhoni doktorët në modulin e përdoruesit dhe administratorit.</p><br>
        <a href="./doktoret.php" class="btn">Menaxho</a>
    </div>
    <div class="card">
        <h3><i class='bx bxs-user-detail'></i> Menaxho Specializime</h3>
        <p>Menaxhoni specializimet në modulin e përdoruesit dhe administratorit.</p>
        <a href="./specializimi.php" class="btn">Menaxho</a>
    </div>
    <div class="card">
        <h3><i class='bx bxs-user-detail'></i> Menaxho Kontaktet</h3>
        <p>Menaxhoni kontaktet në modulin e përdoruesit dhe administratorit. </p><br>
        <a href="./kontakti.php" class="btn">Menaxho</a>
    </div>
</div>

<script>
    const menuToggle = document.getElementById("menu-toggle");
    const navMenu = document.getElementById("nav-menu");

    menuToggle.addEventListener("click", () => {
        navMenu.classList.toggle("active");
    });
</script>
<br><br><br><br>
<?php include_once '../footer.php'; ?>
</body>
</html>
