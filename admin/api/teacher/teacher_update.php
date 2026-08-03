<?php 
include "../connect.php";
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");
header("Content-Type: application/json");

$data1 = json_decode(file_get_contents("php://input"), true);

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($data1['op'])) {
    // Fetch department details for editing
    if ($data1['op'] == "select_condition") {
        $teacher_id = mysqli_real_escape_string($con, $data1['idd']);
        
        $query = "SELECT * FROM teacher WHERE id = '$teacher_id'";
        $result = mysqli_query($con, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
            echo json_encode(["status" => "success", "data" => $row]);
        } else {
            echo json_encode(["status" => "error", "message" => "No data found"]);
        }
    }
    
    // Update department details
    elseif ($data1['op'] == "edit") {
        $user=$_SESSION['name'];
        $teacher_id = mysqli_real_escape_string($con, $data1['id']);
        $teacher = mysqli_real_escape_string($con, $data1['teacher']);
        $designation = mysqli_real_escape_string($con, $data1['designation']);
        $dept_name = mysqli_real_escape_string($con, $data1['dept_name']);
        
        $query = "UPDATE teacher SET teacher='$teacher', designation = '$designation', dept_name = '$dept_name', user='$user' WHERE id = '$teacher_id'";
        
        if (mysqli_query($con, $query)) {
            echo json_encode(["status" => "success", "message" => "Teacher updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "SQL Error: " . mysqli_error($con)]);
        }
    }
    
    else {
        echo json_encode(["status" => "error", "message" => "Invalid Operation"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
}

mysqli_close($con);
?>