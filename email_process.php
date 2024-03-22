<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require "cred.php";

class OTPSender {
    private $senderEmail;
    private $senderPassword;

    public function __construct(string $senderEmail, string $senderPassword) {
        $this->senderEmail = $senderEmail;
        $this->senderPassword = $senderPassword;
    }
    public function sendEmail(string $recipientEmail, string $token_hash): void {
        $mail = new PHPMailer(true);
        try {
            // Setting up server settings.
            $mail->isSMTP();
            $mail->SMTPAuth = true;

            $mail->Host = 'smtp.gmail.com';
            $mail->Username = $this->senderEmail;
            $mail->Password = $this->senderPassword;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom($this->senderEmail);
            $mail->addAddress($recipientEmail);

            $mail->isHTML(true);
            $mail->Subject = 'Reset Password';
            $mail->Body =  "Click <a href='http://example.com/SQL/SQl/new_password.php?token=$token_hash'>here</a> to reset your password.";

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function send_otp(string $recipientEmail, string $otp): void {
        $mail = new PHPMailer(true);
        try {
            // Setting up server settings.
            
            $mail->Subject = 'Validate OTP';
            $mail->Body =  "Your OTP is $otp.";

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
