<?php
session_start();
include '../db/db.php';

if (!isset($_GET['date']) || empty($_GET['date'])) {
    echo json_encode(['availableTimes' => []]);
    exit;
}

$date = $_GET['date'];
$dayOfWeek = date('N', strtotime($date));


if ($dayOfWeek > 5) {
    echo json_encode(['availableTimes' => []]);
    exit;
}

$availableTimes = ["10:00", "12:00", "14:00", "16:00"];


$sql = "SELECT appointment_date FROM reservations WHERE DATE(appointment_date) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $date);
$stmt->execute();
$result = $stmt->get_result();

$reservedTimes = [];
while ($row = $result->fetch_assoc()) {
    $reservedTimes[] = date('H:i', strtotime($row['appointment_date']));
}


$freeTimes = array_diff($availableTimes, $reservedTimes);

echo json_encode(['availableTimes' => array_values($freeTimes)]);
?>
