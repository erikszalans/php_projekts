<?php
session_start();
include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['message' => 'Lūdzu, piesakieties, lai pievienotu produktus grozam.']);
        exit;
    }

    $userId = $_SESSION['user_id'];
    $productId = intval($data['productId']); 

    $stmt = $conn->prepare("INSERT IGNORE INTO cart (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $userId, $productId);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Produkts pievienots grozam!']);
    } else {
        echo json_encode(['message' => 'Neizdevās pievienot produktu.']);
    }
    $stmt->close();
    $conn->close();
}
?>
