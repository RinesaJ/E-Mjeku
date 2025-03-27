<?php
session_start();

// Verifikimi nëse përdoruesi është i kyçur
if (!isset($_SESSION['ID_Perdoruesi'])) {
    header("Location: ../../kycu.php"); // Rrjedhja për në faqen e kyçjes nëse nuk është kyçur
    exit();
}

// Marrja e ID të doktorit nga URL
if (!isset($_GET['doctor_id'])) {
    die("Doctor ID is missing!");
}
$doctor_id = intval($_GET['doctor_id']); // Sanitize the input

// Lidhja me bazën e të dhënave
$host = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "smd";

$conn = new mysqli($host, $dbUser, $dbPassword, $dbName);

// Verifikimi i gabimeve të lidhjes
if ($conn->connect_error) {
    die("Gabim në lidhje me bazën e të dhënave: " . $conn->connect_error);
}

// Marrja e detajeve të doktorit
$sql = "SELECT * FROM doktoret WHERE ID_Doktori = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Doktori nuk u gjinde!");
}

$doctor = $result->fetch_assoc();
// Marrja e mesazheve
$sql_messages = "SELECT * FROM mesazhet_chat WHERE (id_msg_hyrse = ? AND id_msg_dalse = ?) OR (id_msg_hyrse = ? AND id_msg_dalse = ?) ORDER BY msg_id ASC";
$stmt = $conn->prepare($sql_messages);

// Kontrolloni nëse pyetja është përgatitur me sukses
if ($stmt === false) {
    die('Gabim në përgatitjen e pyetjes: ' . $conn->error);
}

$stmt->bind_param("iiii", $doctor_id, $_SESSION['ID_Perdoruesi'], $_SESSION['ID_Perdoruesi'], $doctor_id);
$stmt->execute();
$result_messages = $stmt->get_result();

// Kontrolloni nëse ka rreshta të dhënash për të marrë
if ($result_messages->num_rows > 0) {
    $messages = [];
    while ($row = $result_messages->fetch_assoc()) {
        $messages[] = $row;
    }
} else {
    $messages = [];
}

$stmt->close(); // Mbyllni stmt vetëm nëse është ekzekutuar me sukses

?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat me <?php echo htmlspecialchars($doctor['EmriMbiemri']); ?></title>
    <link rel="stylesheet" href="./css/chat.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
      /* Styles për chat */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    text-decoration: none;
    font-family: 'Poppins', sans-serif;
}
body {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    background: #fff;
    padding: 0 10px;
}
.wrapper {
    background: #B3E5FC;
    max-width: 800px;
    width: 100%;
    border-radius: 8px;
    box-shadow: 0 0 128px 0 rgba(0,0,0,0.1), 0 32px 64px -48px rgba(0,0,0,0.5);
    color: #333 !important;
    display: flex;
    flex-direction: row;
}
.zona-e-bisedës {
    flex: 3;
    padding: 25px 30px;
}
.kuti-e-bisedës {
    position: relative;
    min-height: 300px;
    max-height: 450px; /* Mund të rregulloni këtë vlerë për të përshtatur dizajnin */
    overflow-y: auto;
    padding: 10px 30px 20px 30px;
    background: #3d89ac;
    box-shadow: inset 0 32px 32px -32px rgb(0 0 0 / 5%), inset 0 -32px 32px -32px rgb(0 0 0 / 5%);
    overflow-wrap: break-word; /* Përshtatja e fjalëve të gjata */
}
.kuti-e-bisedës .bisedë {
    margin: 15px 0;
}
.kuti-e-bisedës .bisedë p {
    word-wrap: break-word;
    padding: 8px 16px;
    background: #B3E5FC;
    border-radius: 18px;
    line-height: 1.5; /* Rregulloni hapësirën midis rreshtave për lexueshmëri */
    max-width: auto; /* Kufizoni gjerësinë për të mbajtur mesazhin të menaxhueshëm */
}
.kuti-e-bisedës .dalëse .detaje {
    margin-left: auto;
    max-width: calc(100% - 130px);
}
.dalëse .detaje p {
    background: #B3E5FC;
    color: #333;
    border-radius: 18px 18px 0 18px;
}
.kuti-e-bisedës .hyrëse {
    display: flex;
    align-items: flex-end;
}
.kuti-e-bisedës .hyrëse .detaje {
    margin-right: auto;
    margin-left: 10px;
    max-width: calc(100% - 130px);
}
.hyrëse .detaje p {
    background: #fff;
    color: #333;
    border-radius: 18px 18px 18px 0;
}
.zona-e-shkrimit {
    padding: 18px 30px;
    display: flex;
    justify-content: space-between;
}
.zona-e-shkrimit input {
    height: 45px;
    width: calc(100% - 58px);
    font-size: 16px;
    padding: 0 13px;
    border: 1px solid #e6e6e6;
    outline: none;
    border-radius: 5px 0 0 5px;
}
.zona-e-shkrimit input:focus {
    border: 1px solid #3d89ac;
}
.zona-e-shkrimit button {
    color: #fff;
    width: 55px;
    border: none;
    outline: none;
    background: #3d89ac;
    font-size: 19px;
    cursor: pointer;
    opacity: 0.7;
    pointer-events: none;
    border-radius: 0 5px 5px 0;
    transition: all 0.3s ease;
}
.zona-e-shkrimit button:hover {
    background: #2c5f77;
}
.zona-e-shkrimit button.aktive {
    opacity: 1;
    pointer-events: auto;
}
@media screen and (max-width: 550px) {
    .wrapper {
        flex-direction: column;
    }
}
    </style>
</head>
<body>
    <div class="wrapper">
        <section class="zona-e-bisedës">
            <div class="buton" style="color: #333;">
                <a href="../ballina.php"><i class='bx bxs-left-arrow-alt'></i> Ballina</a>
                <div class="logedin" style="float: right; background-color: white; border-radius: 30px; padding: 10px 10px;">
                    <i class='bx bxs-user-circle'></i>
                    <?php if (isset($_SESSION["Perdoruesi"]) && !empty($_SESSION["Perdoruesi"])): ?>
                        <?php echo htmlspecialchars($_SESSION["Perdoruesi"]); ?>
                    <?php else: ?>
                        <a href="../kycu.php"><i class='bx bx-log-in'></i> Kycu</a>
                    <?php endif; ?>
                </div>
            </div>

            <header>
                <div class="detaje">
                    <span id="emri-doktorit">Jeni duke shkruar me <?php echo htmlspecialchars($doctor['EmriMbiemri']); ?></span>
                </div>
            </header>

            <div id="kuti-e-bisedës" class="kuti-e-bisedës">
    <?php foreach ($messages as $message): ?>
        <?php
        // Kontrollo nëse mesazhi është dërguar nga përdoruesi ose doktori
        $isOutgoing = ($message['id_msg_hyrse'] == $_SESSION['ID_Perdoruesi']);
        ?>
        <div class="bisedë <?php echo $isOutgoing ? 'dalëse' : 'hyrëse'; ?>">
            <div class="detaje">
                <p><?php echo htmlspecialchars($message['msg']); ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>


            <form action="#" class="zona-e-shkrimit" id="message-form">
                <input type="hidden" id="id-perdoruesit" name="id_msg_hyrse" value="<?php echo $_SESSION['ID_Perdoruesi']; ?>">
                <input type="hidden" id="id-doktorit" name="id_msg_dalse" value="<?php echo $doctor_id; ?>">
                <input type="hidden" name="ID_Doktori" value="<?php echo $doctor_id; ?>">
                <input type="hidden" name="ID_Perdoruesi" value="<?php echo $_SESSION['ID_Perdoruesi']; ?>">
                <input type="hidden" id="tipi-derguesit" name="tipi_derguesit" value="Perdoruesi">
                <input type="text" id="mesazhi" name="msg" class="input-field" placeholder="Shkruaj mesazhin tuaj..." autocomplete="off">
                <button id="dergo" type="submit" class="aktive"><i class='bx bx-paper-plane'></i></button>
            </form>
        </section>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const userId = <?php echo $_SESSION['ID_Perdoruesi']; ?>;
        const doctorId = <?php echo $doctor_id; ?>;

        // Function to add message to chat box
        function addMessage(message, isOutgoing) {
            const chatBox = document.getElementById('kuti-e-bisedës');
            const messageClass = isOutgoing ? 'dalëse' : 'hyrëse';
            const messageHtml = `
                <div class="bisedë ${messageClass}">
                    <div class="detaje">
                        <p>${message}</p>
                    </div>
                </div>
            `;
            chatBox.innerHTML += messageHtml;
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        // Handle form submission
        document.getElementById('message-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const message = document.getElementById('mesazhi').value;

            if (message.trim() !== '') {
                addMessage(message, true);
                document.getElementById('mesazhi').value = '';

                // Send message via fetch
                fetch('dergo_mesazhin.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        id_msg_hyrse: userId,
                        id_msg_dalse: doctorId,
                        msg: message,
                        tipi_derguesit: 'Perdoruesi',
                        ID_Doktori: doctorId,
                        ID_Perdoruesi: userId
                    })
                })
                .then(response => response.text())
                .then(response => {
                    console.log("Server response:", response);
                })
                .catch(error => {
                    console.error("Gabim gjatë dërgimit të mesazhit:", error);
                });
            }
        });
    });
</script>

</body>
</html>