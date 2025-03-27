<?php
session_start();

// Check if the doctor is logged in
if (!isset($_SESSION['ID_Doktori'])) {
    header("Location: ../../kycu.php"); // Redirect to login page if not logged in
    exit();
}

// Database connection
$host = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "smd";

$conn = new mysqli($host, $dbUser, $dbPassword, $dbName);

// Check for connection errors
if ($conn->connect_error) {
    die("Gabim në lidhje me bazën e të dhënave: " . $conn->connect_error);
}

// Fetch users who have sent messages to the doctor
$doctor_id = $_SESSION['ID_Doktori'];
$sql = "SELECT DISTINCT p.ID_Perdoruesi, p.EmriMbiemri 
        FROM mesazhet_chat m 
        JOIN perdoruesit p ON p.ID_Perdoruesi = m.id_msg_hyrse 
        WHERE m.id_msg_dalse = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Doktori</title>
    <link rel="stylesheet" href="./css/chat.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* Add your chat interface styles here */
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
            display: flex;
            width: 100%;
            max-width: 1200px;
            background: #B3E5FC;
            border-radius: 8px;
            box-shadow: 0 0 128px 0 rgba(0,0,0,0.1), 0 32px 64px -48px rgba(0,0,0,0.5);
        }
        .user-list {
            flex: 1;
            padding: 20px;
            border-right: 1px solid #ddd;
            overflow-y: auto;
        }
        .user-list h3 {
            margin-bottom: 20px;
        }
        .user-list ul {
            list-style: none;
            padding: 0;
        }
        .user-list ul li {
            margin-bottom: 10px;
        }
        .user-list ul li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            cursor: pointer;
        }
        .user-list ul li a:hover {
            text-decoration: underline;
        }
        .chat-container {
            flex: 3;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }
        .chat-container header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .chat-container .kuti-e-bisedës {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            background: #3d89ac;
            border-radius: 8px;
        }
        .chat-container .kuti-e-bisedës .bisedë {
            margin-bottom: 10px;
        }
        .chat-container .kuti-e-bisedës .bisedë p {
            word-wrap: break-word;
            padding: 8px 16px;
            background: #B3E5FC;
            border-radius: 18px;
        }
        .chat-container .kuti-e-bisedës .dalëse .detaje p {
            background: #B3E5FC;
        }
        .chat-container .kuti-e-bisedës .hyrëse .detaje p {
            background: #fff;
        }
        .zona-e-shkrimit {
            display: flex;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        .zona-e-shkrimit input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px 0 0 5px;
            outline: none;
        }
        .zona-e-shkrimit button {
            padding: 10px 20px;
            background: #3d89ac;
            border: none;
            color: #fff;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            opacity: 1; /* Ensure the button is fully visible */
            pointer-events: auto; /* Ensure the button is clickable */
            transition: all 0.3s ease;
        }

        .zona-e-shkrimit button:hover {
            background: #2c5f77;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="user-list">
            <h3>Përdoruesit që kanë dërguar mesazhe</h3>
            <ul id="user-list">
                <?php foreach ($users as $user): ?>
                    <li>
                        <a href="#" class="user-link" data-user-id="<?php echo $user['ID_Perdoruesi']; ?>">
                            <?php echo htmlspecialchars($user['EmriMbiemri']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="chat-container">
        <header style="display: flex; justify-content: space-between; align-items: center; color: #333; padding: 10px;">
    <div class="buton">
        <a href="../profili.php"><i class='bx bxs-left-arrow-alt'></i> Profili</a>
    </div>
    <div class="logedin" style="background-color: white; border-radius: 30px; padding: 10px 20px; z-index: 1000; display: flex; align-items: center;">
        <i class='bx bxs-user-circle'></i>
        <?php if (isset($_SESSION["Perdoruesi"]) && !empty($_SESSION["Perdoruesi"])): ?>
            <?php echo htmlspecialchars($_SESSION["Perdoruesi"]); ?>
        <?php else: ?>
            <a href="../../kycu.php"><i class='bx bx-log-in'></i> Kycu</a>
        <?php endif; ?>
    </div>
</header>


            <div id="kuti-e-bisedës" class="kuti-e-bisedës">
                <!-- Messages will be displayed here -->
            </div>

            <form action="#" class="zona-e-shkrimit" id="message-form">
                <input type="hidden" id="id-doktorit" name="id_msg_hyrse" value="<?php echo $_SESSION['ID_Doktori']; ?>">
                <input type="hidden" id="id-perdoruesit" name="id_msg_dalse" value="">
                <input type="hidden" name="tipi_derguesit" value="Doktori">
                <input type="text" id="mesazhi" name="msg" placeholder="Shkruaj mesazhin tuaj..." autocomplete="off">
                <button id="dergo" type="submit"><i class='bx bx-paper-plane'></i></button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let selectedUserId = null;
            const doctorId = <?php echo $_SESSION['ID_Doktori']; ?>;

            // Function to fetch messages
            function fetchMessages() {
                if (selectedUserId !== null) {
                    fetch(`merr_mesazhet.php?id_msg_hyrse=${doctorId}&id_msg_dalse=${selectedUserId}`)
                        .then(response => response.json())
                        .then(data => {
                            const chatBox = document.getElementById('kuti-e-bisedës');
                            chatBox.innerHTML = ''; // Clear previous messages

                            data.forEach(message => {
                                const isOutgoing = message.id_msg_hyrse == doctorId;
                                const messageClass = isOutgoing ? 'dalëse' : 'hyrëse';

                                const messageElement = `
                                    <div class="bisedë ${messageClass}">
                                        <div class="detaje">
                                            <span class="time">${message.koha}</span>
                                            <p>${message.msg}</p>
                                        </div>
                                    </div>`;
                                chatBox.innerHTML += messageElement;
                            });

                            // Scroll to the bottom of the chat box
                            chatBox.scrollTop = chatBox.scrollHeight;
                        })
                        .catch(error => console.error("Gabim gjatë marrjes së mesazheve:", error));
                }
            }

            // Refresh messages every 1 second
            setInterval(fetchMessages, 1000);

            // Handle the send button click event
            document.getElementById('message-form').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent form submission

    const msg = document.getElementById('mesazhi').value.trim(); // Get the message content

    if (msg !== '' && selectedUserId !== null) {
        fetch('dergo_mesazhin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                id_msg_hyrse: doctorId,
                id_msg_dalse: selectedUserId,
                msg: msg,
                tipi_derguesit: 'Doktori'
            })
        })
        .then(response => response.text())
        .then(response => {
            console.log("Mesazhi u dërgua me sukses");
            document.getElementById('mesazhi').value = ''; // Clear the input field
            fetchMessages(); // Refresh the messages after sending
        })
        .catch(error => console.error("Gabim gjatë dërgimit të mesazhit:", error));
    }
});

            // Handle user click
            const userLinks = document.querySelectorAll('.user-link');
            userLinks.forEach(link => {
                link.addEventListener('click', function () {
                    selectedUserId = this.getAttribute('data-user-id');
                    document.getElementById('id-perdoruesit').value = selectedUserId;
                    fetchMessages();
                });
            });
        });
    </script>
</body>
</html>