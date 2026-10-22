<?php
include "../connect.php";

header("Content-Type: application/json");

$data1 = json_decode(file_get_contents("php://input"), true);

if (isset($data1['op']) && $data1['op'] == "view") {
    // Students, teachers and subjects per department (departments with no records at all are skipped)
    $query = "SELECT d.dept_name,
                 (SELECT COUNT(*) FROM student s WHERE s.std_dept = d.dept_name) AS students,
                 (SELECT COUNT(*) FROM teacher t WHERE t.dept_name = d.dept_name) AS teachers,
                 (SELECT COUNT(*) FROM subjects sb WHERE sb.dept_name = d.dept_name) AS subjects
              FROM (SELECT DISTINCT dept_name FROM dept) d
              HAVING students + teachers + subjects > 0
              ORDER BY students DESC, teachers + subjects DESC";
    $result = mysqli_query($con, $query);

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $row['students'] = (int) $row['students'];
        $row['teachers'] = (int) $row['teachers'];
        $row['subjects'] = (int) $row['subjects'];
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
