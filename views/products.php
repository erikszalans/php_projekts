<?php
// database connection
include '../db/db.php';

// Fetch data
$sql = "SELECT name, price, image_url, description FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../visual/css/products_style.css">
    <title>Products</title>
</head>
<body>
    <!-- Navigācijas josla -->
    <header>
        <div class="navbar">
            <img src="logo.png" alt="Logo" class="logo">
            <nav>
                <ul>
                    <li><a href="patik.php">Patīk</a></li>
                    <li><a href="grozs.php">Grozs</a></li>
                    <li><a href="index.php">Sākumlapa</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Produktu sadaļa -->
    <h1 style="text-align: center; margin-top: 20px;">Mūsu Produkti</h1>
    <div id="products-container">
        <?php
        // Check if there are rows in the result
        if ($result->num_rows > 0) {
            // Output data for each row
            while ($row = $result->fetch_assoc()) {
                echo '<div class="product">';
                echo '<img src="' . htmlspecialchars($row["image_url"]) . '" alt="' . htmlspecialchars($row["name"]) . '">';
                echo '<h2>' . htmlspecialchars($row["name"]) . '</h2>';
                echo '<p class="description">' . htmlspecialchars($row["description"]) . '</p>';
                echo '<p class="price">' . htmlspecialchars($row["price"]) . ' €</p>';
                echo '<button class="like-button">❤️ Patīk</button>';
                echo '<button class="cart-button">🛒 Pievienot grozam</button>';
                echo '</div>';
            }
        } else {
            echo "<p style='text-align: center;'>Nav atrasti produkti.</p>";
        }

        // Close the connection
        $conn->close();
        ?>
    </div>
</body>
</html>
