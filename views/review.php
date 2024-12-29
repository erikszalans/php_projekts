<?php
session_start();
require_once "../db/db.php"; // Savienojums ar datubāzi

// Ielogojies lietotājs?
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : null;

// Apstrādā atsauksmes pievienošanu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isLoggedIn) {
    $user_id = $_SESSION['user_id'];
    $review = trim($_POST['review']);
    $rating = intval($_POST['rating']); // Pārliecinieties, ka reitings ir skaitlis no 1-5

    if (!empty($review) && $rating >= 1 && $rating <= 5) {
        $stmt = $conn->prepare("INSERT INTO feedback (user_id, message, rating) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $user_id, $review, $rating);
        $stmt->execute();
        $stmt->close();
    }
}

// Atsauksmju lappušu iestatīšana
$reviewsPerPage = 5;
$page = max(1, filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT));
$offset = ($page - 1) * $reviewsPerPage;

// Iegūst atsauksmes ar ierobežojumu
$stmt = $conn->prepare("SELECT feedback.*, users.username FROM feedback JOIN users ON feedback.user_id = users.id ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $reviewsPerPage, $offset);
$stmt->execute();
$result = $stmt->get_result();
$reviews = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Iegūst kopējo atsauksmju skaitu un reitings summu
$result = $conn->query("SELECT SUM(rating) AS totalRating, COUNT(*) AS totalCount FROM feedback");
$data = $result->fetch_assoc();
$totalReviews = (int)$data['totalCount'];
$totalRatingSum = (int)$data['totalRating'];

// Aprēķina vidējo reitingu
$averageRating = $totalReviews ? $totalRatingSum / $totalReviews : 0;

// Aprēķina kopējo lapu skaitu
$totalPages = ceil($totalReviews / $reviewsPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../visual/css/style.css">
    <title>Klientu Atsauksmes</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
            font-family: Arial, sans-serif;
        }
        .reviews-container {
            max-width: 800px;
            width: 100%;
            margin: 20px;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .review-form, .statistics, .pagination {
            margin-bottom: 20px;
            text-align: center;
        }
        .review-form textarea, .review-form button {
            width: 100%;
            margin-bottom: 15px;
        }
        .stars {
            display: inline-block;
            position: relative;
            font-size: 2rem;
            color: #ccc;
        }
        .stars input {
            display: none;
        }
        .stars label {
            float: right;
            cursor: pointer;
        }
        .stars label:hover,
        .stars label:hover ~ label,
        .stars input:checked ~ label {
            color: #FFD700; /* Gold */
        }
        .review-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: #f9f9f9;
        }
        .pagination a {
            margin: 0 5px;
            text-decoration: none;
            color: #007BFF;
        }
        .pagination a.active {
            font-weight: bold;
            text-decoration: underline;
        }
        .statistics p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
<div class="reviews-container">
<a href="index.php">Atpakaļ uz sākumlapu</a>
    <h1>Klientu Atsauksmes</h1>
    <?php if ($isLoggedIn): ?>
        <!-- Atsauksmes pievienošanas forma -->
        <form class="review-form" action="review.php" method="post">
            <label for="review">Jūsu atsauksme:</label>
            <textarea id="review" name="review" rows="4" minlength="15" required></textarea>
            <label>Reitings:</label>
            <div class="stars">
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" required>
                    <label for="star<?= $i ?>" title="<?= $i ?> stars">&#9733;</label>
                <?php endfor; ?>
            </div>
            <button type="submit">Iesniegt Atsauksmi</button>
        </form>
    <?php else: ?>
        <p><a href="login.php">Ielogojieties</a>, lai pievienotu atsauksmi.</p>
    <?php endif; ?>

    <!-- Parāda atsauksmes -->
    <?php foreach ($reviews as $review): ?>
        <div class="review-card">
            <h3><?= htmlspecialchars($review['username']) ?></h3>
            <div class="rating-stars">
                <?= str_repeat('&#9733;', $review['rating']) ?>
                <?= str_repeat('&#9734;', 5 - $review['rating']) ?>
            </div>
            <p><?= htmlspecialchars($review['message']) ?></p>
        </div>
    <?php endforeach; ?>

    <!-- Statistikas sadaļa -->
    <div class="statistics">
        <h2>Statistika</h2>
        <p>Atsauksmes kopā: <?= $totalReviews ?></p>
        <p>Vidējais reitings: <?= number_format($averageRating, 1) ?> / 5</p>
    </div>

    <!-- Paginācija -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>">Iepriekšējā</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>">Nākamā</a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
