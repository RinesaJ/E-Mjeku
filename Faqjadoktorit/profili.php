<?php
include_once 'konfigurimi.php';

if (!isset($_SESSION['Perdoruesi'])) {
    header("Location: ../kycu.php"); 
    exit();
}

$timeout_duration = 1800;

$perdoruesi = $_SESSION['Perdoruesi']; 
$query = $conn->prepare("SELECT * FROM doktoret WHERE EmriMbiemri = ?");
$query->bind_param("s", $perdoruesi);
$query->execute();
$result = $query->get_result();
$doctor = $result->fetch_assoc();

if (!$doctor) {
    $_SESSION['error_message'] = "Doktori nuk u gjet!";
    header("Location: regjistrohu_doktor.php"); 
}
function getSpecializationName($id, $conn) {
    $query = $conn->prepare("SELECT Specializimi FROM specializimi WHERE ID_Specializimi = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();
    return $row ? $row['Specializimi'] : 'Nuk gjindet ';
}

// Function to get location name
function getLocationName($id, $conn) {
    $query = $conn->prepare("SELECT Lokacioni FROM lokacioni WHERE ID_Lokacioni = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();
    return $row ? $row['Lokacioni'] : 'Nuk gjindet';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/Faqjadoktori/profili.css" />
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
                <li><a href="../Faqjauser/ballina.php">Ballina</a></li>
                <li>
                    <div class="chat-icon">
                        <a href="chat" title="Kontakto Doktorin">
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

<div class="profile-container">
    <?php
    if (isset($_SESSION['success_message'])) {
        echo '<div id="message" class="alert alert-success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
        unset($_SESSION['success_message']);
    }

    if (isset($_SESSION['error_message'])) {
        echo '<div id="message" class="alert alert-danger">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
        unset($_SESSION['error_message']);
    }
    ?>

    <div class="profile-image">
        <img src="data:image/jpeg;base64,<?php echo base64_encode($doctor['Foto']); ?>" alt="Profile Image" style="width: 150px; height: 150px;">
    </div>
    <form action="ruaj.php" method="POST" enctype="multipart/form-data">
        <label for="numri_licences">Numri i Licencës:</label><br>
        <input type="text" name="numri_licences" id="numri_licences" value="<?php echo htmlspecialchars($doctor['NumriLicences']); ?>" class="input-field" readonly><br><br>

        <label for="emri_mbiemri">Emri dhe Mbiemri:</label><br>
        <input type="text" name="emri_mbiemri" id="emri_mbiemri" value="<?php echo htmlspecialchars($doctor['EmriMbiemri']); ?>" class="input-field" required><br><br>

        <label for="id_specializimi">Specializimi:</label><br>
        <select name="id_specializimi" id="id_specializimi" class="input-field" required>
            <option value="<?php echo $doctor['ID_Specializimi']; ?>"><?php echo getSpecializationName($doctor['ID_Specializimi'], $conn); ?></option>
            <?php
            $specializimiQuery = $conn->query("SELECT ID_Specializimi, Specializimi FROM specializimi");
            while ($row = $specializimiQuery->fetch_assoc()) {
                echo "<option value='{$row['ID_Specializimi']}'>{$row['Specializimi']}</option>";
            }
            ?>
        </select><br><br>

        <label for="id_lokacioni">Lokacioni:</label><br>
        <select name="id_lokacioni" id="id_lokacioni" class="input-field" required>
            <option value="<?php echo $doctor['ID_Lokacioni']; ?>"><?php echo getLocationName($doctor['ID_Lokacioni'], $conn); ?></option>
            <?php
            $lokacioniQuery = $conn->query("SELECT ID_Lokacioni, Lokacioni FROM lokacioni");
            while ($row = $lokacioniQuery->fetch_assoc()) {
                echo "<option value='{$row['ID_Lokacioni']}'>{$row['Lokacioni']}</option>";
            }
            ?>
        </select><br><br>

        <label for="foto">Foto:</label><br>
        <input type="file" name="foto" id="foto" class="input-field" accept="image/*"><br><br>

        <div class="social-media">
            <i class='bx bxl-facebook'></i><input type="url" name="facebook_url" value="<?php echo htmlspecialchars($doctor['facebook_url']); ?>" class="social-box">
            <i class='bx bx-globe'></i><input type="url" name="website_url" value="<?php echo htmlspecialchars($doctor['website_url']); ?>" class="social-box"><br>
            <i class='bx bxl-linkedin-square'></i><input type="url" name="likedin_url" value="<?php echo htmlspecialchars($doctor['linkedin_url']); ?>" class="social-box">
            <i class='bx bxl-instagram'></i><input type="url" name="instagram_url" value="<?php echo htmlspecialchars($doctor['instagram_url']); ?>" class="social-box">
        </div>

        <button type="submit" class="submit-btn"><i class='bx bxs-save'></i>Save</button>
    </form>
</div>

<script>
    window.onload = function() {
        var messageDiv = document.getElementById("message");
        if (messageDiv && messageDiv.innerHTML.trim() !== "") {
            messageDiv.style.display = "block";
            setTimeout(function() {
                messageDiv.style.display = "none";
            }, 3000);
        }
    }
</script>
<script>
    const menuToggle = document.getElementById("menu-toggle");
    const navMenu = document.getElementById("nav-menu");

    menuToggle.addEventListener("click", () => {
        navMenu.classList.toggle("active");
    });
</script>
<br><br>
<?php include_once '../footer.php'; ?>
</body>
</html>


