<?php
session_start();
include '../db/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validations
    if (empty($username)) {
        $errors[] = "Lietotājvārds ir nepieciešams";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Nepareizs epasta formāts";
    }
    if (strlen($password) < 6) {
        $errors[] = "Parolei jābūt vismaz 6 rakstzīmes garai!";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Paroles nesakrīt";
    }

    // If no errors, proceed to register user
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username,$password_hash, $email);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Reģistrācija neizdevās. Mēģiniet vēlreiz.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="../visual/css/login_style.css">
</head>
<body>
    <div class="container">
        <div class="registration-form">
            <h2>Reģistrācija</h2>
            <?php if (!empty($errors)): ?>
                <ul class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <form action="register.php" method="POST">
                <div class="form-group">
                    <label>Lietotājvārds</label>
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <label>E-pasts</label>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label>Parole</label>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <label>Apstiprināt paroli</label>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                </div>
                <button type="submit">Reģistrēties</button>
            </form>
            <p>Jau ir izveidots profils? <a href="login.php">Pieteikties</a></p>
        </div>
    </div>
</body>
</html>