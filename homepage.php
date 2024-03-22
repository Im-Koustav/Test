<?php
session_start();
if (!isset($_SESSION["data"])) {
    header("location: login.php");
    exit();
}
// Autoloader for classes
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

if (!isset($_SESSION["data"])) {
    header("location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // $databaseConnection = new DatabaseConnection();
    $pdfGenerator = new PDFGenerator();

    $full_name = $_POST["f_name"] . ' ' . $_POST["l_name"];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $phoneNumber = $_POST["phone_number"];
    $emailId = $_POST["email_id"];
    $marksText = $_POST["subject_marks"];
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    $image = $target_file;

    // Generate PDF
    $pdfGenerator->generatePDF($full_name, $emailId, $phoneNumber, $image, $marksText);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Entry</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <p>Provide only Alphabets and name, surname shouldn't exceed 20 letters:</p>
        <form method="post" enctype="multipart/form-data">
            <!-- Taking input from the user. -->   
            <label for="first_name">First name:</label>
            <input type="text" id="first_name" name="f_name" pattern="^[A-Za-z]{1,20}$" required oninput="fullName()">

            <label for="last_name">Last name:</label>
            <input type="text" id="last_name" name="l_name" pattern="^[A-Za-z]{1,20}$" required oninput="fullName()">

            <!-- Printing the name. -->
            <label for="full_name">Full name:</label>
            <input type="text" id="full_name" readonly>

            <label for="image">Upload Image:</label>
            <input type="file" name="image" id="image" accept="image/*" >

            <label for="subject_marks">Subject Marks (Format: Subject|Marks):</label>
            <textarea id="subject_marks" name="subject_marks" rows="5" ></textarea>

            <label for="ph_number">Ph Number:</label>
            <input type="tel" id="ph_number" name="phone_number" pattern="\+91[0-9]{1,10}">

            <label for="email_id">Email ID:</label>
            <input type="text" id="email_id" name="email_id">

            <input type="submit" value="Submit">
        </form>
        <p>
            <a href="session_out.php">Log Out</a>
        </p>
    </div> 
</body>
</html>
