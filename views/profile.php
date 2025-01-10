<?php
session_start();
include '../db/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo '<p style="text-align: center;">Jums vajag <a href="login.php">pieteikties</a>, lai piekļūtu profilam.</p>';
    exit;
}

$user_id = $_SESSION['user_id'];


$sql_user = "SELECT username, created_at FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_result = $stmt_user->get_result();
$user_data = $user_result->fetch_assoc();


$sql_reviews = "SELECT id, rating, message, created_at FROM feedback WHERE user_id = ?";
$stmt_reviews = $conn->prepare($sql_reviews);
$stmt_reviews->bind_param("i", $user_id);
$stmt_reviews->execute();
$reviews_result = $stmt_reviews->get_result();


$sql_reservations = "SELECT appointment_date FROM reservations WHERE user_id = ?";
$stmt_reservations = $conn->prepare($sql_reservations);
$stmt_reservations->bind_param("i", $user_id);
$stmt_reservations->execute();
$reservations_result = $stmt_reservations->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profils</title>
    <link rel="stylesheet" href="../visual/css/profile.css">
</head>
<body>
<div class="profile-container">
    <div class="profile-header">
        <?php if ($user_data): ?>
            <h2><?php echo htmlspecialchars($user_data['username']); ?></h2>
            <p>Pievienojās: <?php echo htmlspecialchars(date("F d, Y", strtotime($user_data['created_at']))); ?></p>
            <p>Atsauksmes kopā: <?php echo $reviews_result->num_rows; ?></p>
        <?php else: ?>
            <p>Kļūda: Nevar piekļūt lietotāja datiem.</p>
        <?php endif; ?>
    </div>


    <div class="review-section">
        <h3>Jūsu atsauksmes</h3>
        <?php if ($reviews_result->num_rows > 0): ?>
            <?php while ($review = $reviews_result->fetch_assoc()): ?>
                <div class="review-card">
                    <p><strong>Reitings:</strong> 
                        <?php 
                        echo str_repeat("&#9733;", $review['rating']);
                        echo str_repeat("&#9734;", 5 - $review['rating']);
                        ?>
                    </p>
                    <p><?php echo htmlspecialchars($review['message']); ?></p>
                    <p><small>Publicēts: <?php echo htmlspecialchars(date("F d, Y", strtotime($review['created_at']))); ?></small></p>
                    <div class="review-actions">
                        <a href="edit_review.php?id=<?php echo $review['id']; ?>" class="btn edit-btn">Rediģēt</a>
                        <a href="delete_review.php?id=<?php echo $review['id']; ?>" class="btn delete-btn" onclick="return confirm('Vai tiešām vēlaties dzēst šo atsauksmi?');">Dzēst</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Jūs neesat publicējis nevienu atsauksmi.</p>
        <?php endif; ?>
    </div>

    <div class="reservation-section">
        <h3>Jūsu rezervācijas</h3>
        <?php if ($reservations_result->num_rows > 0): ?>
            <ul>
                <?php while ($reservation = $reservations_result->fetch_assoc()): ?>
                    <li>
                        <p><strong>Datums:</strong> <?php echo htmlspecialchars(date("F d, Y, H:i", strtotime($reservation['appointment_date']))); ?></p>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Jums nav rezervāciju.</p>
        <?php endif; ?>
    </div>

    <a href="index.php" class="back-home-btn">Atgriesties</a>
</div>
</body>
</html>
