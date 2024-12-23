<?php
session_start();
$errors = [];
include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if fields are empty
    if (empty($username) || empty($password)) {
        $errors[] = "Visi lauki ir obligāti!";
    } else {
        // Prepare and execute the SQL statement
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username); // Correctly bind the username variable
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Redirect to index.php
                header("Location: ../views/index.php");
                exit();
            } else {
                $errors[] = "Nepareizs lietotājvārds vai parole.";
            }
        } else {
            $errors[] = "Nepareizs lietotājvārds vai parole.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pieteikties</title>
    <link rel="stylesheet" href="../visual/css/login_style.css">
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h2>Pieteikties</h2>
            <?php if (!empty($errors)): ?>
                <ul class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label>Lietotājvārds</label>
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <label>Parole</label>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit">Pieteikties</button>
            </form>
            <p>Nav izveidots profils? <a href="register.php">Reģistrēties</a></p>
        </div>
    </div>
</body>
</html>