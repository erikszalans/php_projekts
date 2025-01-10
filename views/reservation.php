<?php
include '../db/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$sql = "SELECT appointment_date FROM reservations;";
$reservedSlots = $conn->query($sql);
$reservedTimes = [];
if ($reservedSlots && $reservedSlots->num_rows > 0) {
    while ($row = $reservedSlots->fetch_assoc()) {
        $dateTime = strtotime($row['appointment_date']);
        $date = date('Y-m-d', $dateTime);
        $time = date('H:i', $dateTime);
        $reservedTimes[$date][] = $time;
    }
} else if (!$reservedSlots) {
    error_log("Database query failed: " . $conn->error);
}


$availableTimes = [
    "10:00", "12:00", "14:00", "16:00"
];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id']; 
    $date = $_POST['reservation_date'];
    $time = $_POST['reservation_time'];
    $appointmentDate = "$date $time";

    // Validate workday
    $dayOfWeek = date('N', strtotime($date));
    if ($dayOfWeek > 5) {
        $error = "Rezervācijas iespējamas tikai darba dienās.";
    } elseif (in_array($time, $reservedTimes[$date] ?? [])) {
        $error = "Izvēlētais laiks jau ir aizņemts.";
    } else {
        // Check if the user already has a reservation for the same date and time
        $checkSql = "SELECT id FROM reservations WHERE user_id = ? AND appointment_date = ?";
        $stmt = $conn->prepare($checkSql);
        $stmt->bind_param('is', $userId, $appointmentDate);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Jūs jau esat rezervējuši šo laiku.";
        } else {
            
            $insertSql = "INSERT INTO reservations (user_id, appointment_date) VALUES (?, ?)";
            $stmt = $conn->prepare($insertSql);
            $stmt->bind_param('is', $userId, $appointmentDate);
            if ($stmt->execute()) {
                $success = "Rezervācija veiksmīgi pievienota!";
                $reservedTimes[$date][] = $time; 
            } else {
                $error = "Radās kļūda, mēģiniet vēlreiz.";
            }
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
    <script src="../visual/js/update_slots.js"></script>
</head>
<body>
    
    <h1>Rezervācijas sistēma</h1>
    <a href="index.php" class="back-button">Atgriezties uz sākumu</a>

    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>

    <form method="post" action="">
        <label for="reservation_date">Izvēlies datumu:</label>
        <input type="date" id="reservation_date" name="reservation_date" required min="<?php echo date('Y-m-d'); ?>" onchange="updateTimeSlots()">

        <label for="reservation_time">Izvēlies laiku:</label>
        <select id="reservation_time" name="reservation_time" required>
            <option value="">-- Izvēlies laiku --</option>
        </select>

        <button type="submit">Pieteikties</button>
    </form>

    
</body>
</html>
