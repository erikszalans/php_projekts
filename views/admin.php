<?php
session_start();
include '../db/db.php';

// Ensure only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'administrators') {
    header("Location: ../views/index.php");
    exit();
}

// Fetch all users from the database
$query = "SELECT id, username, email FROM users";
$result = $conn->query($query);

if (!$result) {
    die("Error fetching users: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lietotāji</title>
    <link rel="stylesheet" href="../visual/css/admin_style.css"> 
</head>
<body>
    <li><a href="index.php">Sākumlapa</a></li>
    <div class="container">
        <h1>Lietotāji</h1>
        <p>Kopā: <?php echo $result->num_rows; ?></p>
        <table>
            <thead>
                <tr>
                    <th>Vārds</th>
                    <th>E-pasts</th>
                    <th>Darbības</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <a href="user_details.php?id=<?php echo $user['id']; ?>" class="btn">Skatīt</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
