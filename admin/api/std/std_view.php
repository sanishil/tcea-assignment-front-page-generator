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
    $query = "SELECT id,std_id,tu_roll,name,sem,std_dept,course,subjects,dou FROM student;";
    $result = mysqli_query($con, $query);
    
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    if (empty($data)) {
        echo json_encode(["status" => "error", "message" => "No data has been found"]);
    } else {
        echo json_encode(["status" => "success", "data" => $data]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
}

mysqli_close($con);
?>
