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

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email)) {
        $errors['email'] = "Email is required";
    }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT * FROM auth_users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
        } else {
            $errors['login'] = "Invalid email or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css?v=1.0">
</head>
<body>
    <form method="POST" action="">
        <h2>Login</h2>
        <?php if (isset($errors['login'])): ?>
            <div class="error"><?php echo $errors['login']; ?></div>
        <?php endif; ?>
        <?php if (isset($errors['success'])): ?>
            <div class="error"><?php echo $errors['success']; ?></div>
        <?php endif; ?>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? '', ENT_QUOTES); ?>" >
        <?php if (isset($errors['email'])): ?>
            <div class="error"><?php echo $errors['email']; ?></div>
        <?php endif; ?>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <?php if (isset($errors['password'])): ?>
            <div class="error"><?php echo $errors['password']; ?></div>
        <?php endif; ?>
        
        <button type="submit">Login</button>
        <a href="forgot_password.php">Forgot Password?</a>
        <a href="registration.php">Create account?</a>
    </form>
</body>
</html>
