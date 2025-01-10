<?php
session_start();
include '../db/db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['message' => 'Lūdzu, piesakieties, lai pievienotu produktus patīk.']);
        exit;
    }

    $userId = $_SESSION['user_id'];
    $productId = intval($data['productId']);

    $stmt = $conn->prepare("INSERT IGNORE INTO wishlist (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $userId, $productId);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Produkts pievienots patīk!']);
    } else {
        echo json_encode(['message' => 'Neizdevās pievienot produktu.']);
    }
    $stmt->close();
    $conn->close();
}
?>
