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
    $dept_name = mysqli_real_escape_string($con, $data1['dept_name']);
    $hod = mysqli_real_escape_string($con, $data1['hod']);

    if (!empty($dept_name) && !empty($hod)) {
        $query = "INSERT INTO dept (dept_name, hod, user) VALUES ('$dept_name', '$hod', '$user')";
        
        if (mysqli_query($con, $query)) {
            echo json_encode(["status" => "success", "message" => "Department added successfully"]);
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