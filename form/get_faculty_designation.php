<?php
include "../tech/api/connect.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Read JSON data from AngularJS
$data = json_decode(file_get_contents("php://input"), true);

// Debug: Log received data
//file_put_contents("debug.log", "Received Data: " . print_r($data, true) . "\n", FILE_APPEND);

if (isset($data['f_dept']) && isset($data['f_name'])) {
    $dept_name = mysqli_real_escape_string($con, $data['f_dept']);
    $teacher_name = mysqli_real_escape_string($con, $data['f_name']); 

    // Debug: Log SQL query
    $query = "SELECT designation FROM teacher WHERE dept_name = '$dept_name' AND teacher = '$teacher_name'";
    // file_put_contents("debug.log", "SQL Query: $query\n", FILE_APPEND);

    $result = mysqli_query($con, $query);
    $designationList = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $designationList[] = $row['designation'];
    }

    if (!empty($designationList)) {
        echo json_encode(["status" => "success", "data" => $designationList]);
    } else {
        echo json_encode(["status" => "error", "message" => "No designation found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}

mysqli_close($con);
?>
