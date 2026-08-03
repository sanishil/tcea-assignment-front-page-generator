<?php 
include "../connect.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$data1 = json_decode(file_get_contents("php://input"), true);

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($data1['op']) && $data1['op'] == "deactivate") {
    $u_id = mysqli_real_escape_string($con, $data1['idd']);
    
    if (!empty($u_id)) {
        $query = "UPDATE login SET token=0 WHERE id = '$u_id'";
        
        if (mysqli_query($con, $query)) {
            echo json_encode(["status" => "success", "message" => "ID Deactivate successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "SQL Error: " . mysqli_error($con)]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid ID"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
}

mysqli_close($con);
?>
