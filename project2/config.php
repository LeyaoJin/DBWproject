<?php
$servername = "localhost"; 
$username = "indimomap"; 
$password = "dbw_indimap"; 
$dbname = "BioinfoDB";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

