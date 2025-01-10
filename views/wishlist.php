<?php
session_start();
include '../db/db.php';

if (!isset($_SESSION['user_id'])) {
    echo '<p style="text-align: center;">Lai piekļūtu patīk, jums ir jāpieslēdzas.</p>';
    echo '<div style="text-align: center; margin-top: 20px;">
            <a href="login.php" style="padding: 10px 20px; background-color: #ad8a34; color: white; text-decoration: none; font-weight: bold; border-radius: 5px;">
                Pieslēgties
            </a>
          </div>';
    exit;
}

$userId = $_SESSION['user_id'];
$sql = "SELECT p.id, p.name, p.price, p.image_url 
        FROM products p
        JOIN wishlist w ON p.id = w.product_id
        WHERE w.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../visual/css/products_style.css">
    <title>Patīk</title>
</head>
<body>
    <?php
    include 'navbar.php'; 
    ?>
    <h1>Mans Patīk Saraksts</h1>
    <div id="wishlist-container">
        <?php
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product">';
            echo '<img src="' . htmlspecialchars($row["image_url"]) . '" alt="' . htmlspecialchars($row["name"]) . '">';
            echo '<h2>' . htmlspecialchars($row["name"]) . '</h2>';
            echo '<p class="price">' . htmlspecialchars($row["price"]) . ' €</p>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>
