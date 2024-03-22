<?php

session_start();
if (isset($_SESSION["data"])) {
    header("location: homepage.php");
    exit();
  }

include("connect.php");
// UserAuthentication class for handling user authentication and login
class UserAuthentication {
    private $conn;

    // Constructor to initialize the database connection
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Method to authenticate user login
    public function authenticateUser($mailId, $password) {
        $query = "SELECT * FROM Signup WHERE mail_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $mailId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $userData = $result->fetch_assoc();
            if (password_verify($password, $userData['password'])) {
                return true;
            }
        }
        return false;
    }
}

// Creating an instance of UserAuthentication class with database connection
$userAuthentication = new UserAuthentication($conn);

// Handling form submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $mailId = $_POST['mailId'];
    $password = $_POST['password'];

    // Authenticating user login
    if ($userAuthentication->authenticateUser($mailId, $password)) {
        $_SESSION["data"] = $mailId;
        header('location: homepage.php');
        exit;
    } else {
        echo "<script type='text/javascript'>alert('Invalid username or password');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log In</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container">
    <form method="post">

      <label for="mailId">Mail ID</label>
      <input type="text" name="mailId">

      <label for= "password">Password</label>
      <input type="password" name="password">

      <input type="submit" value="Submit">
    </form>
    <p>
      Don't have an account? <a href="signup.php">SignUp Here</a>
    </p>
    <p><a href="forgot_password.php">Forgot Password?</a></p>
  </div>
</body>
</html>
