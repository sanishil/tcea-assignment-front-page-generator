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
    $name = mysqli_real_escape_string($con, $data1['name']);
    $user_id = mysqli_real_escape_string($con, $data1['user_id']);
    $pass = mysqli_real_escape_string($con, $data1['pass']);
    $type = mysqli_real_escape_string($con, $data1['type']);

    if (!empty($name) && !empty($user_id) && !empty($pass) && !empty($type)) {
        $query = "INSERT INTO login (name, user_id, pass, type, user) VALUES ('$name', '$user_id','$pass','$type', '$user')";
        
        if (mysqli_query($con, $query)) {
            echo json_encode(["status" => "success", "message" => "User added successfully"]);
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