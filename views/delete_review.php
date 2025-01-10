<?php
session_start();
include '../db/db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: profile.php");
    exit();
}

$review_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$sql = "DELETE FROM feedback WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $review_id, $user_id);
$stmt->execute();

header("Location: profile.php");
exit();
?>
