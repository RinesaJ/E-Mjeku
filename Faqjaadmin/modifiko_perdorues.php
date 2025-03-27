<?php
include_once '../konfigurimi.php';

if (!isset($_GET['id'])) {
    header("Location: perdoruesit.php");
    exit;
}

$id = $_GET['id'];


if (!$conn) {
    die("Lidhja deshtoi: " . $conn->connect_error);
}

$conn->set_charset("utf8");

$stmt = $conn->prepare("SELECT EmriMbiemri, Fjalekalimi, Email FROM perdoruesit WHERE ID_Perdoruesi = ?");
if (!$stmt) {
    die("Gabim SQL: " . $conn->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: perdoruesit.php");
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();

$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];  
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($password)) {
        $errorMessage = "Ju lutem plotësoni të gjitha fushat!";
    };

        $stmt = $conn->prepare("UPDATE perdoruesit SET EmriMbiemri = ?, Email = ?, Fjalekalimi = ? WHERE ID_Perdoruesi = ?");
        if (!$stmt) {
            die("SQL error: " . $conn->error);
        }


        $stmt->bind_param("sssi", $name, $email, $password, $id); 

        if ($stmt->execute()) {
            $successMessage = "Përdoruesi u përditësua me sukses!";
        } else {
            $errorMessage = "Gabim gjatë përditësimit: " . $conn->error;
        }
        $stmt->close();
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
    <title>E-Mjeku - Përditëso Përdorues</title>
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
    <h2>Përditëso Përdorues</h2>

    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success" id="successMessage"><?php echo htmlspecialchars($successMessage); ?></div>
        <script>
            setTimeout(function() {
                window.location.href = "perdoruesit.php";
            }, 3000); 
        </script>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Emri</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['EmriMbiemri']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="Email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Fjalëkalimi</label>
            <input type="text" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($user['Fjalekalimi']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Modifiko Përdorues</button>
        <a href="perdoruesit.php" class="btn btn-secondary">Kthehu</a>
    </form>
</div>
<br><br>
<?php
$conn->close();
 include_once '../footer.php'; ?>

</body>
</html>
