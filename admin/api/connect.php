<?php
$servername = "sql202.infinityfree.com";
$username   = "if0_39849507";
$password   = "Rupanjana"; // replace with your vPanel password
$database   = "if0_39849507_assignment";

// Create connection
$con = new mysqli($servername, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>
