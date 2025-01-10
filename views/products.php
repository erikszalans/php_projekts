<?php
include 'navbar.php'; 
include '../db/db.php';



$sql = "SELECT id, name, price, image_url, description FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../visual/css/products_style.css">
    <title>Products</title>
    <script src="../visual/js/wishlist.js" defer></script>
    <script src="../visual/js/cart.js" defer></script>
</head>
<body>


    <h1 style="text-align: center; margin-top: 20px;">Mūsu Produkti</h1>
    <div id="products-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="product">';
                echo '<img src="' . htmlspecialchars($row["image_url"]) . '" alt="' . htmlspecialchars($row["name"]) . '">';
                echo '<h2>' . htmlspecialchars($row["name"]) . '</h2>';
                echo '<p class="description">' . htmlspecialchars($row["description"]) . '</p>';
                echo '<p class="price">' . htmlspecialchars($row["price"]) . ' €</p>';
                echo '<button class="like-button" onclick="addToWishlist(' . $row["id"] . ')">❤️ Patīk</button>';
                echo '<button class="cart-button" onclick="addToCart(' . $row["id"] . ')">🛒 Pievienot grozam</button>';
                echo '</div>';
            }
        } else {
            echo "<p style='text-align: center;'>Nav atrasti produkti.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
