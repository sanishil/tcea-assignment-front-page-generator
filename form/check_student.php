<?php
include "../tech/api/connect.php";

header("Content-Type: application/json");

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['std_id'])) {
    $std_id = $data['std_id'];

    // Use Prepared Statement to Prevent SQL Injection
    $query = "SELECT std_id, tu_roll, title, name, sem, std_dept FROM student WHERE std_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $std_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode([
            "status" => "found",
            "tu_roll" => $row['tu_roll'],
            "title" => $row['title'],
            "name" => $row['name'],
            "sem" => $row['sem'],
            "std_dept" => $row['std_dept'],
        ]);
    } else {
        echo json_encode(["status" => "not_found"]);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
}

mysqli_close($con);
?>
