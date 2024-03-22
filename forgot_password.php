<?php

  session_start();
  if (isset($_SESSION["data"])) {
    header("location: homepage.php");
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <form action="reset_password.php" method="post">
            
            <label for="email">Enter your email:</label>
            <input type="email" name="email" required>

            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
