<?php

// DatabaseConnection class for handling database connection
class DatabaseConnection {
    private $conn;

    // Constructor to initialize the database connection
    public function __construct($servername, $username, $password, $dbname) {
        $this->conn = new mysqli($servername, $username, $password, $dbname);
        
        // Check if connection is successful
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Method to get the database connection
    public function getConnection() {
        return $this->conn;
    }
}

// Include credentials
require 'cred.php';

// Create an instance of DatabaseConnection class with credentials
$databaseConnection = new DatabaseConnection($servername, $username, $password, $dbname);

// Get database connection
$conn = $databaseConnection->getConnection();
