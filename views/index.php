<?php
session_start();
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kāzu salons</title>
    <link rel="stylesheet" href="../visual/css/style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <header class="main-header">
        <div class="logo-container">
            <img src="visual/images/logo-black.png" alt="Logo" class="logo">
            <h1 class="logo-text">SAPŅU KLEITA</h1>
        </div>
        <div class="header-icons">
            <a href="#" class="icon-link"><img src="visual/images/heart-icon-black.png" alt="Patīk"></a>
            <a href="#" class="icon-link"><img src="visual/images/cart-icon-black.png" alt="Grozs"></a>
            <?php if (isset($_SESSION['username'])): ?>
                <div class="icon-link dropdown">
                    <img src="visual/images/user-icon-black.png">
                    <span><?php echo $_SESSION['username']; ?></span>
                    <div class="dropdown-menu">
                        <a href="logout.php">Izrakstīties</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="auth-links">
                    <a href="login.php" class="icon-link">
                        <img src="visual/images/user-icon-black.png" alt="Pieslēgties">
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </header>
    <!-- Navigation Menu -->
    <nav class="nav-bar">
        <ul class="nav-links">
            <li class="nav-item dropdown">
                <a href="products.php" class="dropdown-toggle">Kāzu kleitas</a>
            </li>
            <li class="nav-item"><a href="#">Rezervācija</a></li>
            <li class="nav-item"><a href="review.php">Atsauksmes</a></li>
            <li class="nav-item"><a href="#">Profils</a></li>
        </ul>
    </nav>
</body>
</html>