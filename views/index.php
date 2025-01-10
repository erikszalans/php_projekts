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
    
    <header class="main-header">
        <div class="logo-container">
            <a href="index.php" class="logo-link">
                <img src="../visual/images/logi.png" alt="Logo" class="logo">
            </a>
        </div>
        <div class="header-icons">
            <a href="wishlist.php" class="icon-link">
                <img src="../visual/images/patikk.png" alt="Patīk">
            </a>
            <a href="cart.php" class="icon-link">
                <img src="../visual/images/grozs.png" alt="Grozs">
            </a>
            <?php if (isset($_SESSION['username'])): ?>
                <div class="icon-link dropdown">
                    <img src="../visual/images/profils.jpg">
                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <div class="dropdown-menu">
                        <a href="logout.php">Izrakstīties</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="auth-links">
                    <a href="login.php" class="icon-link">
                        <img src="../visual/images/profils.jpg" alt="Pieslēgties">
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </header>
    
    
    <nav class="nav-bar">
        <ul class="nav-links">
            <li class="nav-item"><a href="products.php">Kāzu kleitas</a></li>
            <li class="nav-item"><a href="reservation.php">Rezervācija</a></li>
            <li class="nav-item"><a href="review.php">Atsauksmes</a></li>
            <li class="nav-item"><a href="profile.php">Profils</a></li>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'administrators'): ?>
                <li class="nav-item"><a href="admin.php">Admins</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    
    <section class="hero-section">
        <div class="hero-container">
            <img src="../visual/images/index.jpg" alt="Hero Image" class="hero-image">
            <div class="hero-overlay">
                <h1 class="hero-title">SAPŅU KLEITA</h1>
                <p class="hero-subtitle">DIZAINERU KĀZU KLEITAS</p>
                <a href="products.php" class="cta-button">Apskatīt Kāzu Kleitas</a>
            </div>
        </div>
    </section>
</body>
</html>
