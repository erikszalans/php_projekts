<?php
session_start();
include '../db/db.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'administrators') {
    header("Location: ../views/index.php");
    exit();
}


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Lietotāja ID nav norādīts vai ir nepareizs.");
}
$userId = intval($_GET['id']);

$userQuery = "SELECT username, email, created_at FROM users WHERE id = ?";
$stmt = $conn->prepare($userQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$userResult = $stmt->get_result();
$user = $userResult->fetch_assoc();

if (!$user) {
    die("Lietotājs netika atrasts.");
}


$feedbackQuery = "SELECT message, rating, created_at FROM feedback WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($feedbackQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$feedbackResult = $stmt->get_result();


$reservationsQuery = "SELECT appointment_date FROM reservations WHERE user_id = ? ORDER BY appointment_date DESC";
$stmt = $conn->prepare($reservationsQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$reservationsResult = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lietotāja detaļas</title>
    <link rel="stylesheet" href="../visual/css/user_details_style.css"> 
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($user['username']); ?></h1>
        <p><strong>E-pasts:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Pievienojās:</strong> <?php echo htmlspecialchars(date("F d, Y", strtotime($user['created_at']))); ?></p>

        <h2>Lietotāja atsauksmes</h2>
        <?php if ($feedbackResult->num_rows > 0): ?>
            <ul>
                <?php while ($feedback = $feedbackResult->fetch_assoc()): ?>
                    <li>
                        <strong>Atsauksme:</strong> <?php echo htmlspecialchars($feedback['message']); ?><br>
                        <strong>Vērtējums:</strong> 
                        <?php 
                        echo str_repeat("&#9733;", $feedback['rating']);
                        echo str_repeat("&#9734;", 5 - $feedback['rating']);
                        ?><br>
                        <strong>Izveidots:</strong> <?php echo htmlspecialchars(date("F d, Y", strtotime($feedback['created_at']))); ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Lietotājam nav pieejamas atsauksmes.</p>
        <?php endif; ?>

        <h2>Lietotāja rezervācijas</h2>
        <?php if ($reservationsResult->num_rows > 0): ?>
            <ul>
                <?php while ($reservation = $reservationsResult->fetch_assoc()): ?>
                    <li>
                        <strong>Rezervācijas datums:</strong> <?php echo htmlspecialchars(date("F d, Y, H:i", strtotime($reservation['appointment_date']))); ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Lietotājam nav rezervāciju.</p>
        <?php endif; ?>

        <a href="admin.php" class="btn">Atpakaļ</a>
    </div>
</body>
</html>
