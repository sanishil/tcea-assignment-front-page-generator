<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");
header("Content-Type: application/json");

session_start();
include 'connect.php';

// Get JSON input
$input = json_decode(file_get_contents("php://input"), true);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Validate input data
if (!isset($input['current_pass']) || !isset($input['npass']) || !isset($input['cpass'])) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}

$current_pass = mysqli_real_escape_string($con, $input['current_pass']);
$npass = mysqli_real_escape_string($con, $input['npass']);
$cpass = mysqli_real_escape_string($con, $input['cpass']);

// Check if new password and confirm password match
if ($npass !== $cpass) {
    echo json_encode(["status" => "error", "message" => "New password and confirm password do not match"]);
    exit;
}

// Get current password from database
$sql = "SELECT pass FROM login WHERE user_id = '$user_id'";
$res = mysqli_query($con, $sql);

if (!$res) {
    echo json_encode(["status" => "error", "message" => "Database error: " . mysqli_error($con)]);
    exit;
}

if (mysqli_num_rows($res) === 0) {
    echo json_encode(["status" => "error", "message" => "User not found"]);
    exit;
}

$row = mysqli_fetch_array($res);
$db_password = $row['pass'];

// Verify current password
if ($db_password !== $current_pass) {
    echo json_encode(["status" => "error", "message" => "Current password is incorrect"]);
    exit;
}

// Update password
$query = "UPDATE login SET pass = '$npass' WHERE user_id = '$user_id'";

if (mysqli_query($con, $query)) {
    echo json_encode(["status" => "success", "message" => "Password updated successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update password: " . mysqli_error($con)]);
}

mysqli_close($con);
?>