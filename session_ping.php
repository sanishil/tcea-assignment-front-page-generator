<?php
// Called by assets/js/idle_logout.js while the user is active,
// so the server-side session timer stays in sync with the browser.

define('SESSION_TIMEOUT', 600); // 10 minutes

session_start();
include 'tech/api/connect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['last_activity'])
   || time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
   echo json_encode(["status" => "expired"]);
   exit;
}

$_SESSION['last_activity'] = time();
$stmt = $con->prepare("UPDATE login SET last_activity=NOW(), dou=dou WHERE user_id=?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
mysqli_close($con);
echo json_encode(["status" => "ok"]);
?>
