<?php

require_once("connect.php");
require "cred.php";
require_once("email_process.php");

class SignupHandler {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function processSignup() {
        session_start();
        if (isset($_SESSION["data"])) {
            header("location: homepage.php");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_POST['submit_user_details'])) {
                $this->handleUserDetailsSubmission();
            } elseif (isset($_POST['submit_otp']) && isset($_SESSION['submitted_user_details'])) {
                $this->handleOtpSubmission();
            }
        }
    }

    private function handleUserDetailsSubmission() {
        $name = $this->sanitizeInput($_POST['name']);
        $mailId = $this->sanitizeInput($_POST['mailId']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        if (!empty($name) && !empty($mailId) && !empty($password)) {
            $checkQuery = "SELECT * FROM Signup WHERE mail_id = '$mailId'";
            $result = mysqli_query($this->conn, $checkQuery);

            if (mysqli_num_rows($result) > 0) {
                echo '<script type="text/javascript">alert("Mail ID already exists !!");</script>';
            } else {
                if (filter_var($mailId, FILTER_VALIDATE_EMAIL)) {
                    if ($this->isEmailValid($mailId)) {
                        $otp_str = str_shuffle("123456789");
                        $sentOtp = substr($otp_str, 0 ,4);
                        global $senderEmail,$senderPassword;
                        $otpSender = new OTPSender( $senderEmail,$senderPassword);
                        $otpSender->send_otp($mailId, $sentOtp);

                        $_SESSION['name'] = $name;
                        $_SESSION['mailId'] = $mailId;
                        $_SESSION['password'] = $password;
                        $_SESSION['sentOtp'] = $sentOtp;
                        $_SESSION['submitted_user_details'] = true;
                    } else {
                        echo '<script type="text/javascript">alert("Email Address is not valid !!");</script>';
                    }
                } else {
                    echo '<script type="text/javascript">alert("Invalid Email Format !!");</script>';
                }
            }
        } else {
            echo '<script type="text/javascript">alert("Please provide the required fields !!");</script>';
        }
    }

    private function handleOtpSubmission() {
        $userOtp = $this->sanitizeInput($_POST['otp']);
        $sentOtp = $_SESSION['sentOtp'];

        if ($sentOtp == $userOtp) {
            $name = $_SESSION['name'];
            $mailId = $_SESSION['mailId'];
            $password = $_SESSION['password'];

            $insertQuery = "INSERT INTO Signup (name, mail_id, password) VALUES ('$name' ,'$mailId', '$password')";
            mysqli_query($this->conn, $insertQuery);

            echo '<script type="text/javascript">alert("Data submitted successfully !! Now you can Login !!");</script>';
        } else {
            echo '<script type="text/javascript">alert("OTP not matched !!");</script>';
        }

        unset($_SESSION['submitted_user_details']);
        unset($_SESSION['name']);
        unset($_SESSION['mailId']);
        unset($_SESSION['password']);
        unset($_SESSION['sentOtp']);
    }

    static function sanitizeInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    private function isEmailValid($email) {
        // Implement your email validation logic here
        return true;
    }
}

$signupHandler = new SignupHandler($conn);
$signupHandler->processSignup();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <?php if (!isset($_SESSION['submitted_user_details'])) { ?>
        <form method="post">
            <label for="name">Name</label>
            <input type="text" name="name" required>

            <label for="mailId">Mail ID</label>
            <input type="text" name="mailId" required>

            <label for="password">Password</label>
            <input type="password" name="password" required>

            <input type="submit" name="submit_user_details" value="Submit">
        </form>
    <?php } else { ?>
        <form method="post">
            <label for="otp">Submit OTP here</label>
            <input type="text" name="otp" required>

            <input type="submit" name="submit_otp" value="Submit">
        </form>
    <?php } ?>
    <p>
        Already have an account? <a href="login.php">Login</a>
    </p>
</div>
</body>
</html>
