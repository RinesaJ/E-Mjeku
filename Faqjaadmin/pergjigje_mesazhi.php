<?php
include('../konfigurimi.php');

$message_id = isset($_GET['id']) ? $_GET['id'] : null;
if ($message_id) {

    $query = "SELECT * FROM mesazhet WHERE ID_Mesazhi = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $message = $result->fetch_assoc();

    if (!$message) {
        die('Mesazhi nuk ekziston.');
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reply = $_POST['reply'];
    $email = $message['Email'];

    $subject = "Përgjigje nga admin";
    $body = "Përgjigja juaj: $reply";
    $headers = "From: admin@yourwebsite.com";

    if (mail($email, $subject, $body, $headers)) {
        $_SESSION['success_message'] = "Përgjigja u dërgua me sukses!";

        $update_query = "UPDATE mesazhet SET Statusi = 'Replied' WHERE ID_Mesazhi = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("i", $message_id);
        $update_stmt->execute();
    } else {
        $_SESSION['error_message'] = "Ka ndodhur një gabim gjatë dërgimit të përgjigjes.";
    }

    header("Location: kontakti.php");
    exit();
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
    <title>E-Mjeku - Përgjigje për Mesazhin</title>
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
    <h2>Per përgjigje për mesazhin e <?php echo htmlspecialchars($message['Emri']); ?></h2>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_SESSION['success_message']); ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($_SESSION['error_message']); ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="reply" class="form-label">Përgjigje:</label>
            <textarea class="form-control" id="reply" name="reply" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Dërgo Përgjigje</button>
    </form>
</div>
</body>
</html>

<?php
$conn->close();
?>
