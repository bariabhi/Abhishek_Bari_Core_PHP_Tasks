<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $errors = [];

    if (empty($name)) {
        $errors['name'] = "Name is required!";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    if (!empty($name) && !empty($email)) {
        try {
            $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            header("Location: index.php");
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                // Handle duplicate entry error
                $error = "The email address '$email' is already registered.";
            } else {
                $error = "Error: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/styles.css?v=1.0">
    <title>Create User</title>
</head>
<body>
    <div class="container">
        <h2>Create User</h2>
        <form method="POST" action="">
            <label>Name:</label>
            <input type="text" name="name">
            <?php if (isset($errors['name'])): ?>
                <div class="error"><?php echo $errors['name']; ?></div>
            <?php endif; ?>
            <label>Email:</label>
            <input type="email" name="email">
            <?php if (isset($errors['email'])): ?>
                <div class="error"><?php echo $errors['email']; ?></div>
            <?php endif; ?>
            <button type="submit">Create</button>
        </form>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    </div>
</body>
</html>
