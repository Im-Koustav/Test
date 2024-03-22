<?php

include("connect.php");

class PasswordChanger {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function changePassword($password, $token) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE Signup SET password = '$hashedPassword' WHERE Reset_token_hash = '$token' ";
        $result = mysqli_query($this->conn, $query);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $password = $_POST['password'];
    $token = $_POST['token'];

    $passwordChanger = new PasswordChanger($conn);
    $passwordChanged = $passwordChanger->changePassword($password, $token);

    if ($passwordChanged) {
        ?>
        <script type='text/javascript'>alert('Password changed, Now you can LogIn !!')</script>
        <?php
    }
}
?>
