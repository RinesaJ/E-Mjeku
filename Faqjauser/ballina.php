<?php
include_once '../konfigurimi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>E-Mjeku</title>
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
                    <li><a href="ballina.php">Ballina</a></li>
                    <li><a href="./doktoret.php">Doktoret</a></li>
                    <li><a href="./kontakti.php">Kontakti</a></li>
                    <li>
                        <div class="chat-icon">
                            <a href="chat/index.php" title="Kontakto Doktorin">
                                <img src="../foto/chat_5251476.png" alt="Chat Icon" style="width: 24px; height: 24px; margin-left: 15px;">
                            </a>
                        </div>
                    </li>
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

    <div class="homepage-wrapper">
        <div class="content">
            <h2>Gjeni Doktorin që po kërkoni!</h2>
            <p>
                Platforma jonë ju ndihmon të gjeni lehtësisht mjekë të specializuar në Kosovë bazuar në emër, qytet ose fushë të specializimit. Pavarësisht nëse kërkoni një kardiolog në Prishtinë, një dermatolog në Gjilan, apo një specialist të fushave të tjera, sistemi ynë 
                i kërkimit ju ofron mundësinë të filtroni rezultatet dhe të gjeni shërbimin e duhur mjekësor shpejt dhe me lehtësi.
            </p>
        </div>
        <div class="image-container">
            <img src="../foto/doc4.png" alt="Image">
        </div>
    </div>
    <div class="features">
        <div class="feature">
            <h4><i class='bx bx-search-alt-2'></i> Gjeni doktorët lehtësisht</h4>
            <p>Kërkoni doktorët sipas emrit, qytetit, ose specializimit.</p>
        </div>
        <div class="feature">
            <h4><i class='bx bx-check-double'></i> Profesionistë të verifikuar</h4>
            <p>Të gjithë mjekët verifikohen për kredencialet dhe eksperiencën e tyre.</p>
        </div>
        <div class="feature">
            <h4><i class='bx bx-conversation'></i> Mesazhe direkte</h4>
            <p>Kontakto me mjekun vetëm me disa klikime.</p>
        </div>
    </div>
    <div class="testimonials">
        <h3>Çfarë thonë përdoruesit tanë</h3>
        <div class="testimonial">
            <p>"Kjo platformë më ndihmoi ta gjej me shpejtë doktorë dhe të kontaktoj në mënyrë të drejtëpërdrejtë."</p>
            <span>- F.I</span>
        </div>
    </div>

    <script src="./js/nav.js"></script>
    <script>
        const menuToggle = document.getElementById("menu-toggle");
        const navMenu = document.getElementById("nav-menu");

        menuToggle.addEventListener("click", () => {
            navMenu.classList.toggle("active");
        });
    </script>
    <?php include_once '../footer.php'; ?>
</body>
</html>