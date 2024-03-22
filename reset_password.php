<?php

require_once "connect.php";
require_once "email_process.php";
require "signup.php";
require "cred.php";


session_start();

class PasswordReset {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function sendPasswordResetEmail($email) {
        $checkQuery = "SELECT * FROM Signup WHERE mail_id = '$email'";
        $result = mysqli_query($this->conn, $checkQuery);

        if (mysqli_num_rows($result) > 0) {
            $token = bin2hex(random_bytes(16));
            $token_hash = password_hash($token, PASSWORD_DEFAULT);
            $expiry = date("Y-m-d H:i:s", time() + 60 * 10);
            
            $query = "UPDATE Signup SET Reset_token_hash = '$token_hash', `Token_expiry_time` = '$expiry' WHERE Signup.mail_id = '$email' ";
            mysqli_query($this->conn, $query);
            global $senderEmail,$senderPassword;
            $otpSender = new OTPSender( $senderEmail,$senderPassword);
            $otpSender->sendEmail($email, $token_hash);
        } else {
            echo '<script type="text/javascript"> alert ("Your email is not registered with our database !!")</script>';
        }
    }
}



if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['email'])) {
    $email = SignupHandler::sanitizeInput($_POST['email']);

    $passwordReset = new PasswordReset($conn);
    $passwordReset->sendPasswordResetEmail($email);
}
