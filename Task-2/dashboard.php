<?php
// dashboard.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/styles.css?v=1.0">
    <style>
        .dashboard {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .dashboard h2 {
            margin-bottom: 20px;
            font-size: 28px;
            color: #007bff;
        }

        .dashboard p {
            font-size: 18px;
            margin-bottom: 20px;
            color: #6c757d;
        }

        .dashboard a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .dashboard a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES); ?></h2>
        <p>You have successfully logged in!</p>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
