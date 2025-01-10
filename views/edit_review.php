<?php
session_start();
include '../db/db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: profile.php");
    exit();
}

$review_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Fetch the review
$sql = "SELECT message, rating FROM feedback WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $review_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$review = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_message = $_POST['message'];
    $new_rating = $_POST['rating'];

    $update_sql = "UPDATE feedback SET message = ?, rating = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("siii", $new_message, $new_rating, $review_id, $user_id);
    $stmt->execute();

    header("Location: profile.php");
    exit();
}
?>

<form method="post">
    <label>Atsauksme:</label>
    <textarea name="message"><?php echo htmlspecialchars($review['message']); ?></textarea>
    <label>Reitings:</label>
    <select name="rating">
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <option value="<?php echo $i; ?>" <?php echo ($i == $review['rating']) ? 'selected' : ''; ?>>
                <?php echo $i; ?>
            </option>
        <?php endfor; ?>
    </select>
    <button type="submit">Saglabāt</button>
</form>
