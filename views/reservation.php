<?php
include '../db/db.php';

// Fetch existing reservations
$sql = "SELECT reservation_date, reservation_time FROM reservations WHERE status = 'active'";
$result = $conn->query($sql);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = 1; // Nomaini ar autentifikācijas sistēmu, ja tāda ir
    $date = $_POST['reservation_date'];
    $time = $_POST['reservation_time'];

    // Pārbaude, vai laiks ir brīvs
    $checkSql = "SELECT * FROM reservations WHERE reservation_date = ? AND reservation_time = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param('ss', $date, $time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Izvēlētais laiks jau ir aizņemts.";
    } else {
        // Ierakstīt rezervāciju
        $insertSql = "INSERT INTO reservations (user_id, reservation_date, reservation_time) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param('iss', $userId, $date, $time);
        if ($stmt->execute()) {
            $success = "Rezervācija veiksmīgi pievienota!";
        } else {
            $error = "Radās kļūda, mēģiniet vēlreiz.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervācijas sistēma</title>
    <link rel="stylesheet" href="../visual/css/reservation_style.css">
</head>
<body>
    <h1>Rezervācijas sistēma</h1>
    <a href="index.php" class="back-button">Atgriezties uz sākumu</a>

    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>

    <form method="post" action="">
        <label for="reservation_date">Izvēlies datumu:</label>
        <input type="date" id="reservation_date" name="reservation_date" required>
        
        <label for="reservation_time">Izvēlies laiku:</label>
        <input type="time" id="reservation_time" name="reservation_time" required>
        
        <button type="submit">Pieteikties</button>
    </form>

    <h2>Aizņemtie laiki</h2>
    <ul>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($row['reservation_date']) . " " . htmlspecialchars($row['reservation_time']) . "</li>";
            }
        } else {
            echo "<li>Nav aizņemto laiku</li>";
        }
        ?>
    </ul>
</body>
</html>
