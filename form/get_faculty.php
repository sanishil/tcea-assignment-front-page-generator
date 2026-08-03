<?php
include "../tech/api/connect.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");  // Allow POST instead of GET
header("Access-Control-Allow-Headers: Content-Type");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Read JSON data from AngularJS
$data = json_decode(file_get_contents("php://input"), true);

// Check if f_dept is received
if (isset($data['f_dept'])) {
    $dept_name = mysqli_real_escape_string($con, $data['f_dept']);

    $query = "SELECT teacher FROM teacher WHERE dept_name = '$dept_name'";
    $result = mysqli_query($con, $query);

    $facultyList = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $facultyList[] = $row;
    }

    echo json_encode(["status" => "success", "data" => $facultyList]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}

mysqli_close($con);
?>
