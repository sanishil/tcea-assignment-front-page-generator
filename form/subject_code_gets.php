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

if (isset($data['sem']) && isset($data['std_dept']) && isset($data['course']) && isset($data['subjects'])) {
    $sem = mysqli_real_escape_string($con, $data['sem']);
    $std_dept = mysqli_real_escape_string($con, $data['std_dept']); 
    $course = mysqli_real_escape_string($con, $data['course']); 
    $subjects = mysqli_real_escape_string($con, $data['subjects']); 

    // Use prepared statement to prevent SQL injection
    $query = "SELECT code FROM subjects WHERE sem = ? AND dept_name = ? AND course = ? AND subjects = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $sem, $std_dept, $course, $subjects);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $subjectsList = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $subjectsList[] = $row['code']; // Store only subject codes
    }

    if (!empty($subjectsList)) {
        echo json_encode(["status" => "success", "data" => $subjectsList]);
    } else {
        echo json_encode(["status" => "error", "message" => "No subjects found"]);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}

mysqli_close($con);
?>
