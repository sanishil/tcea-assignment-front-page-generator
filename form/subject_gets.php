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

if (isset($data['sem']) && isset($data['std_dept']) && isset($data['course'])) {
    $sem = mysqli_real_escape_string($con, $data['sem']);
    $std_dept = mysqli_real_escape_string($con, $data['std_dept']); 
    $course = mysqli_real_escape_string($con, $data['course']); 

    // Fetch subjects based on the selected semester, department, and course
    $query = "SELECT subjects FROM subjects 
              WHERE sem = '$sem' AND dept_name = '$std_dept' AND course = '$course'";

    $result = mysqli_query($con, $query);
    $subjectsList = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $subjectsList[] = $row; // Store both subject name and code
    }

    if (!empty($subjectsList)) {
        echo json_encode(["status" => "success", "data" => $subjectsList]);
    } else {
        echo json_encode(["status" => "error", "message" => "No subjects found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}

mysqli_close($con);
?>
