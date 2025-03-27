<?php
include_once '../konfigurimi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numriLicences = isset($_POST['numri_licences']) ? intval($_POST['numri_licences']) : null;
    $emriMbiemri = isset($_POST['emri_mbiemri']) ? trim($_POST['emri_mbiemri']) : '';
    $idSpecializimi = isset($_POST['id_specializimi']) ? intval($_POST['id_specializimi']) : null;
    $idLokacioni = isset($_POST['id_lokacioni']) ? intval($_POST['id_lokacioni']) : null;

    // Handle file upload for the photo
    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']);
    }

    if (!empty($emriMbiemri) && $idSpecializimi && $idLokacioni) {
        $stmt = $conn->prepare("INSERT INTO doktoret (NumriLicences, EmriMbiemri, ID_Specializimi, ID_Lokacioni, Foto) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('isiib', $numriLicences, $emriMbiemri, $idSpecializimi, $idLokacioni, $foto);
        
        if ($stmt->execute()) {
            echo "<p style='color: green;'>Doktori u shtua me sukses!</p>";
        } else {
            echo "<p style='color: red;'>Gabim gjatë shtimit të doktorit: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Ju lutemi plotësoni të gjitha fushat e nevojshme!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shto Doktor</title>
</head>
<body>
    <h2>Shto Doktor</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="numri_licences">Numri i Licencës:</label><br>
        <input type="number" name="numri_licences" id="numri_licences"><br><br>

        <label for="emri_mbiemri">Emri dhe Mbiemri:</label><br>
        <input type="text" name="emri_mbiemri" id="emri_mbiemri" required><br><br>

        <label for="id_specializimi">Specializimi:</label><br>
        <select name="id_specializimi" id="id_specializimi" required>
            <option value="">Zgjidhni Specializimin</option>
            <?php
            $specializimiQuery = $conn->query("SELECT ID_Specializimi, Specializimi FROM specializimi");
            while ($row = $specializimiQuery->fetch_assoc()) {
                echo "<option value='{$row['ID_Specializimi']}'>{$row['Specializimi']}</option>";
            }
            ?>
        </select><br><br>

        <label for="id_lokacioni">Lokacioni:</label><br>
        <select name="id_lokacioni" id="id_lokacioni" required>
            <option value="">Zgjidhni Lokacionin</option>
            <?php
            $lokacioniQuery = $conn->query("SELECT ID_Lokacioni, Lokacioni FROM lokacioni");
            while ($row = $lokacioniQuery->fetch_assoc()) {
                echo "<option value='{$row['ID_Lokacioni']}'>{$row['Lokacioni']}</option>";
            }
            ?>
        </select><br><br>

        <label for="foto">Foto:</label><br>
        <input type="file" name="foto" id="foto" accept="image/*"><br><br>

        <button type="submit">Shto Doktor</button>
    </form>
</body>
</html>
