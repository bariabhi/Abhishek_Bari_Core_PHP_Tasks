<?php
session_start();
include 'config.php'; // Include the configuration file
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    require 'vendor/autoload.php'; // Include the Composer autoload file

    $email = $_POST['email'];

    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT * FROM auth_users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $otp = '1111'; // Static OTP for simplicity
            $reset_link = BASE_URL . "reset_password.php?otp=$otp&email=" . urlencode($email);

            // Configure PHPMailer
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@gmail.com'; // SMTP username
            $mail->Password = 'your_email_password'; // SMTP password
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('no-reply@yourwebsite.com', 'Your Website');
            $mail->addAddress($email); // Add a recipient

            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Click the following link to reset your password: <a href='$reset_link'>$reset_link</a>";

            if ($mail->send()) {
                echo "An email with a reset link has been sent to your email address.";
            } else {
                $errors['email'] = "Failed to send email. Mailer Error: " . $mail->ErrorInfo;
            }
        } else {
            $errors['email'] = "No user found with that email address.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/styles.css?v=1.0">
</head>
<body>
    <form method="POST" action="">
        <h2>Forgot Password</h2>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? '', ENT_QUOTES); ?>" required>
        <?php if (isset($errors['email'])): ?>
            <div class="error"><?php echo $errors['email']; ?></div>
        <?php endif; ?>
        
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>
