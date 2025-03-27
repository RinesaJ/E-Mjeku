<?php 
include_once '../konfigurimi.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $name = $_POST['Emri'];
    $email = $_POST['Email'];
    $message = $_POST['message'];

    if (!empty($name) && !empty($email) && !empty($message)) {

        $query = "INSERT INTO mesazhe (Emri, Email, Mesazhi) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            die('Pregaditja deshtoi: ' . $conn->error);
        }

        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
            $successMessage = "Mesazhi u dërgua me sukses!";
        } else {
            $errorMessage = "Dërgimi i mesazhit dështoi. Ju lutem, provoni përsëri.";
        }
    } else {
        $errorMessage = "Të gjitha fushat janë të kërkuara.";
    }
}

$contact = null;
if (isset($_GET['id'])) {
    $contactId = $_GET['id'];
    $query = "SELECT * FROM mesazhe WHERE ID_Mesazhi = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $contactId);
        $stmt->execute();
        $result = $stmt->get_result();
        $contact = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/kontakti.css" />
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
<body>
   
<div class="wrapper">
    <h2>Kontakto Administratorin</h2>
    <?php if (isset($successMessage)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php elseif (isset($errorMessage)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>
    <script>
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.display = 'none';
        });
    }, 3000); 
</script>

    <form action="kontakti.php" method="POST">
    <div class="mb-3">
        <label for="Emri" class="form-label">Emri Juaj</label>
        <input type="text" class="form-control" id="Emri" name="Emri" required>
    </div>
    <div class="mb-3">
        <label for="Email" class="form-label">Email Juaj</label>
        <input type="email" class="form-control" id="Email" name="Email" required>
    </div>
    <div class="mb-3">
        <label for="message" class="form-label">Mesazhi Juaj</label>
        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
    </div>
    <button type="submit" name="send_message" class="btn btn-primary">Dërgo Mesazhin</button>
</form>

</div>
</div>
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