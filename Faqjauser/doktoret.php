<?php
$host = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "smd";

session_start();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$data = mysqli_connect($host, $dbUser, $dbPassword, $dbName);
if ($data == false) {
    die("Gabim në lidhje!");
}

// Check if there is a search term
$searchTerm = "";
if (isset($_GET['search'])) {
    $searchTerm = mysqli_real_escape_string($data, $_GET['search']);
}

$query = "SELECT d.ID_Doktori, d.EmriMbiemri, s.Specializimi, l.Lokacioni, d.Foto, d.facebook_url, d.website_url, d.linkedin_url, d.instagram_url 
FROM doktoret d
JOIN specializimi s ON d.ID_Specializimi = s.ID_Specializimi
JOIN lokacioni l ON d.ID_Lokacioni = l.ID_Lokacioni";

if (!empty($searchTerm)) {
    $query .= " WHERE d.EmriMbiemri LIKE '%$searchTerm%' OR s.Specializimi LIKE '%$searchTerm%' OR l.Lokacioni LIKE '%$searchTerm%'";
}
$result = $data->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="../css/doktoret.css" />
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
                    <a href="../kycu.php"><i class='bx bxs-log-in'></i> Kycu si Doktor</a>
                </li>
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

<div class="wrapper"><br>
    <div class="search-b" style="position: relative; text-align: center; margin-bottom: 20px; margin-left: 30px;" >
        <form action="doktoret.php" method="GET">
            <input type="text" name="search" placeholder="Kërko për Doktorë: Emri, Qyteti, Specializimi" value="<?php echo htmlspecialchars($searchTerm); ?>" style="float: left; padding: 10px; width: 50%; border-radius: 5px; border: 1px solid #ccc;">
            <button type="submit" style="padding: 10px 15px; border-radius: 5px; background-color: #03ACF2; color: white; border: none; cursor: pointer; float: left; margin-left: 5px;">Kërko</button>
        </form>
        <br>
    </div>

    <br>
    <h2 style="text-align:center;">Informacionet për Doktorët</h2>
    <br>
    <div class="card-container">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card">
    <div class="card-image">
        <?php if (!empty($row['Foto'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['Foto']); ?>" alt="Doctor Image">
        <?php else: ?>
            <p>No Image Available</p>
        <?php endif; ?>
    </div>
    <div class="card-content">
        <h3><?php echo htmlspecialchars($row['EmriMbiemri']); ?></h3>
        <p><strong>Specializimi:</strong> <?php echo htmlspecialchars($row['Specializimi']); ?></p>
        <p><strong>Lokacioni:</strong> <?php echo htmlspecialchars($row['Lokacioni']); ?></p>
        <div class="social-media">
            <?php if (!empty($row['facebook_url'])): ?>
                <a href="<?php echo htmlspecialchars($row['facebook_url']); ?>" target="_blank"><i class='bx bxl-facebook'></i></a>
            <?php endif; ?>
            <?php if (!empty($row['website_url'])): ?>
                <a href="<?php echo htmlspecialchars($row['website_url']); ?>" target="_blank"><i class='bx bx-globe'></i></a>
            <?php endif; ?>
            <?php if (!empty($row['linkedin_url'])): ?>
                <a href="<?php echo htmlspecialchars($row['linkedin_url']); ?>" target="_blank"><i class='bx bxl-linkedin-square'></i></a>
            <?php endif; ?>
            <?php if (!empty($row['instagram_url'])): ?>
                <a href="<?php echo htmlspecialchars($row['instagram_url']); ?>" target="_blank"><i class='bx bxl-instagram'></i></a>
            <?php endif; ?>
        </div>
        <!--chat icona --->
        <div class="chat-icon" style="position: absolute; top: 10px; right: 10px; background-color: #03ACF2; border-radius: 50%; padding: 4px; color: white; font-size: 18px; cursor: pointer;">
            <a href="chat/chat.php?doctor_id=<?php echo $row['ID_Doktori']; ?>" title="Shkruaj <?php echo htmlspecialchars($row['EmriMbiemri']); ?>" style="color: white; text-decoration: none;">
                <i class='bx bx-message-rounded-dots' style="font-size: 22px;"></i>
            </a>
        </div>
    </div>
</div>

            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center;">Nuk jan gjetur informacione për doktorët.</p>
        <?php endif; ?>
    </div><br><br>

<?php include_once '../footer.php'; ?>
<script>
    const menuToggle = document.getElementById("menu-toggle");
    const navMenu = document.getElementById("nav-menu");

    menuToggle.addEventListener("click", () => {
        navMenu.classList.toggle("active");
    });
</script>
</body>
</html>