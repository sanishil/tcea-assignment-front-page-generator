<?php
include "../tech/api/connect.php"; // DB connection

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Read and decode JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($data['op']) && $data['op'] == "insert") {
    // Escape input data
    $std_id = mysqli_real_escape_string($con, $data['std_id']);
    $tu_roll = isset($data['tu_roll']) ? mysqli_real_escape_string($con, $data['tu_roll']) : ""; // optional
    $title = mysqli_real_escape_string($con, $data['title']);
    $name = mysqli_real_escape_string($con, $data['name']);
    $sem = mysqli_real_escape_string($con, $data['sem']);
    $std_dept = mysqli_real_escape_string($con, $data['std_dept']);
    $course = mysqli_real_escape_string($con, $data['course']);
    $subjects = mysqli_real_escape_string($con, $data['subjects']);
    $code = mysqli_real_escape_string($con, $data['code']);
    $page_type = mysqli_real_escape_string($con, $data['page_type']);
    $section = isset($data['section']) ? mysqli_real_escape_string($con, $data['section']) : "";
    $dos = mysqli_real_escape_string($con, $data['dos']);
    $f_dept = mysqli_real_escape_string($con, $data['f_dept']);
    $f_name = mysqli_real_escape_string($con, $data['f_name']);
    $f_designation = mysqli_real_escape_string($con, $data['f_designation']);

    // Check required fields (tu_roll removed from here ✅)
    if (
        !empty($std_id) && !empty($title) && !empty($name) &&
        !empty($sem) && !empty($std_dept) && !empty($course) && !empty($subjects) &&
        !empty($code) && !empty($page_type) && !empty($dos) &&
        !empty($f_dept) && !empty($f_name) && !empty($f_designation)
    ) {

        // Check if std_id exists
        $checkQuery = "SELECT COUNT(*) as cnt FROM student WHERE std_id = '$std_id'";
        $checkResult = mysqli_query($con, $checkQuery);

        if ($checkResult) {
            $row = mysqli_fetch_assoc($checkResult);

            if ($row['cnt'] > 0) {
                // std_id exists --> UPDATE
                $query = "UPDATE student SET 
                    tu_roll = '$tu_roll',
                    title = '$title',
                    name = '$name',
                    sem = '$sem',
                    std_dept = '$std_dept',
                    course = '$course',
                    subjects = '$subjects',
                    code = '$code',
                    page_type = '$page_type',
                    section = '$section',
                    dos = '$dos',
                    f_dept = '$f_dept',
                    f_name = '$f_name',
                    f_designation = '$f_designation'
                  WHERE std_id = '$std_id'";
                $action = "updated";
            } else {
                // std_id does not exist --> INSERT
                $query = "INSERT INTO student (
                    std_id, tu_roll, title, name, sem, std_dept, course, subjects,
                    code, page_type, section, dos, f_dept, f_name, f_designation) 
                    VALUES (
                    '$std_id', '$tu_roll', '$title', '$name', '$sem', '$std_dept', '$course', '$subjects',
                    '$code', '$page_type', '$section', '$dos', '$f_dept', '$f_name', '$f_designation')";
                $action = "created";
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Database error while checking std_id"]);
            exit;
        }

        // Execute query
        if (mysqli_query($con, $query)) {
            echo json_encode([
                "status" => "success",
                "message" => "Front Page " . ucfirst($action) . " Successfully"
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "SQL Error: " . mysqli_error($con)]);
        }

    } else {
        echo json_encode(["status" => "error", "message" => "All required fields must be provided (except tu_roll)"]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
}

mysqli_close($con);
?>
