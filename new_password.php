<?php

session_start();
if (isset($_SESSION["data"])) {
  header("location: homepage.php");
  exit();
}

require_once("connect.php");

class PasswordReset {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function validateToken($token) {
        $query = "SELECT * FROM Signup WHERE Reset_token_hash = '$token' ";
        $result = mysqli_query($this->conn, $query);

        if (!$result) {
            echo '<script type="text/javascript"> alert ("Invalid token or token expired.")</script>';
            return false;
        }

        return true;
    }
}

$token = $_GET["token"];

$passwordReset = new PasswordReset($conn);
$validToken = $passwordReset->validateToken($token);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container">
    <?php if ($validToken) { ?>
    <form action="process_new_password.php" method="post">
      <label for= "password">Password</label>
      <input type="text" name="password">
      <input type="hidden" name="token" value="<?php echo $token;?>">

      <input type="submit" value="Submit">
    </form>
    <?php } ?>
  </div>
</body>
</html>
