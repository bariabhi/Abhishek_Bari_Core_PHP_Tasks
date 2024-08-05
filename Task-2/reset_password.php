<?php
session_start();
include 'config.php'; // Include the configuration file
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'config.php';

    $email = $_POST['email'];
    $otp = $_POST['otp'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($otp) || $otp !== '1111') {
        $errors['otp'] = "Invalid OTP.";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required";
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Passwords do not match";
    }

    if (empty($errors)) {
        $password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("UPDATE auth_users SET password = ? WHERE email = ?");
        $stmt->execute([$password, $email]);
        $errors['success'] = "Password reset successful! Please login with new credentials";
        header("Location: login.php",$errors['success']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/styles.css?1.0">
</head>
<body>
    <form method="POST" action="">
        <h2>Reset Password</h2>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_GET['email'] ?? '', ENT_QUOTES); ?>" readonly>
        
        <label for="otp">OTP:</label>
        <input type="text" id="otp" name="otp" required>
        <?php if (isset($errors['otp'])): ?>
            <div class="error"><?php echo $errors['otp']; ?></div>
        <?php endif; ?>
        
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required>
        <?php if (isset($errors['password'])): ?>
            <div class="error"><?php echo $errors['password']; ?></div>
        <?php endif; ?>
        
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <?php if (isset($errors['confirm_password'])): ?>
            <div class="error"><?php echo $errors['confirm_password']; ?></div>
        <?php endif; ?>
        
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
