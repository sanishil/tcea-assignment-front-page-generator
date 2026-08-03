<?php 
include "../connect.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$data1 = json_decode(file_get_contents("php://input"), true);

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($data1['op']) && $data1['op'] == "view") {
    $query = "SELECT * FROM login";
    $result = mysqli_query($con, $query); // Changed $conn to $con
    
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    if (empty($data)) {
        echo json_encode(["status" => "error", "message" => "No data has been found"]);
    } else {
        echo json_encode(["status" => "success", "data" => $data]);
    }
}
else if (isset($data1['op']) && $data1['op'] == "view2") {
    $query2 = "SELECT * FROM login where token=1";
    $result2 = mysqli_query($con, $query2); // Changed $conn to $con
    
    $data2 = array();
    while ($row2 = mysqli_fetch_assoc($result2)) {
        $data2[] = $row2;
    }
    
    if (empty($data2)) {
        echo json_encode(["status" => "error", "message" => "No data has been found"]);
    } else {
        echo json_encode(["status" => "success", "data" => $data2]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
}

mysqli_close($con); // Changed $conn to $con
?>