<?php
// Redirect if user already logged In!
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'config.php';

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username)) {
        $errors['username'] = "Username is required!";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required!";
    }

    if (empty($confirm_password)) {
        $errors['confirm_password'] = "Confirm password is not be empty!!";
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Passwords do not match";
    }

    if (empty($errors)) {
        $password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO auth_users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password]);
        header("Location: dashboard.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="css/styles.css?v=1.0">
</head>
<body>
    <form method="POST" action="">
        <h2>Register</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username ?? '', ENT_QUOTES); ?>" >
        <?php if (isset($errors['username'])): ?>
            <div class="error"><?php echo $errors['username']; ?></div>
        <?php endif; ?>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? '', ENT_QUOTES); ?>" >
        <?php if (isset($errors['email'])): ?>
            <div class="error"><?php echo $errors['email']; ?></div>
        <?php endif; ?>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" >
        <?php if (isset($errors['password'])): ?>
            <div class="error"><?php echo $errors['password']; ?></div>
        <?php endif; ?>
        
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" >
        <?php if (isset($errors['confirm_password'])): ?>
            <div class="error"><?php echo $errors['confirm_password']; ?></div>
        <?php endif; ?>

        <button type="submit">Register</button>
        <a href="login.php">If you have an account?</a>
    </form>
</body>
</html>
