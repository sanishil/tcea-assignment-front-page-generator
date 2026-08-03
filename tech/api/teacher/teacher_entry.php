<?php 
include "../connect.php";
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$data1 = json_decode(file_get_contents("php://input"), true);

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($data1['op']) && $data1['op'] == "insert") {
    // $user="admin";
    $user=$_SESSION['name'];
    $teacher = mysqli_real_escape_string($con, $data1['teacher']);
    $designation = mysqli_real_escape_string($con, $data1['designation']);
    $dept_name = mysqli_real_escape_string($con, $data1['dept_name']);

    if (!empty($dept_name) && !empty($teacher) && !empty($designation)) {
        $query = "INSERT INTO teacher (teacher,designation,dept_name,user) VALUES ('$teacher', '$designation','$dept_name', '$user')";
        
        if (mysqli_query($con, $query)) {
            echo json_encode(["status" => "success", "message" => "Teacher added successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "SQL Error: " . mysqli_error($con)]);
        }
    } 
    else {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
}

mysqli_close($con);
?>