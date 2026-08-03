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
        $u_id = mysqli_real_escape_string($con, $data1['idd']);
        
        $query = "SELECT * FROM login WHERE id = '$u_id'";
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
        $u_id = mysqli_real_escape_string($con, $data1['id']);
        $name = mysqli_real_escape_string($con, $data1['name']);
        $user_id = mysqli_real_escape_string($con, $data1['user_id']);
        $type = mysqli_real_escape_string($con, $data1['type']);
        
        $query = "UPDATE login SET name = '$name', user_id = '$user_id', type= '$type', user='$user' WHERE id = '$u_id'";
        
        if (mysqli_query($con, $query)) {
            echo json_encode(["status" => "success", "message" => "Department updated successfully"]);
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