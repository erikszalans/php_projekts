<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../visual/css/navbar.css"> 
    <title>SAPŅU KLEITA</title>
</head>
<body>
    <header>
        <div class="navbar">
            <a href="index.php" class="logo">
                <img src="../visual/images/logi.png" alt="Logo"> 
            </a>
            <nav>
                <ul>
                    <li><a href="products.php">Kāzu kleitas</a></li>
                    <li><a href="reservation.php">Rezervācija</a></li>
                    <li><a href="review.php">Atsauksmes</a></li>
                    <li><a href="profile.php">Profils</a></li>
                    <li><a href="wishlist.php">Patīk</a></li>
                    <li><a href="cart.php">Grozs</a></li>

                    <!-- Render the Admin tab only for admin users -->
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'administrators'): ?>
                        <li><a href="admin.php">Admins</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
</body>
</html>
