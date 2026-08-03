<?php 
include "../connect.php";
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$data = json_decode(file_get_contents("php://input"), true);

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($data['op']) && $data['op'] == "insert") {
    // $user = "admin";
    $user=$_SESSION['name'];
    $subjects = mysqli_real_escape_string($con, $data['subjects']);
    $code = mysqli_real_escape_string($con, $data['code']);
    $sem = mysqli_real_escape_string($con, $data['sem']);
    $course = mysqli_real_escape_string($con, $data['course']);
    $dept_name = mysqli_real_escape_string($con, $data['dept_name']);

    if (!empty($subjects) && !empty($code) && !empty($sem) && !empty($course) && !empty($dept_name)) {
        $query = "INSERT INTO subjects (subjects, code, sem, dept_name, course, user) 
                  VALUES ('$subjects', '$code', '$sem', '$dept_name', '$course', '$user')";

        if (mysqli_query($con, $query)) {
            echo json_encode(["status" => "success", "message" => "Subject added successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "SQL Error: " . mysqli_error($con)]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
}

mysqli_close($con);
?>
