<?php
// Included at the top of admin/index.php and tech/index.php.
// Logs the user out after SESSION_TIMEOUT seconds of inactivity.

define('SESSION_TIMEOUT', 600); // 10 minutes

session_start();
include __DIR__ . '/tech/api/connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != true) {
   session_unset();
   session_destroy();
   header("location:../login.html");
   exit;
}

if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
   $stmt = $con->prepare("UPDATE login SET token=0, dou=dou WHERE user_id=?");
   $stmt->bind_param("s", $_SESSION['user_id']);
   $stmt->execute();
   session_unset();
   session_destroy();
   header("location:../login.html?timeout=1");
   exit;
}

$_SESSION['last_activity'] = time();
$stmt = $con->prepare("UPDATE login SET last_activity=NOW(), dou=dou WHERE user_id=?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
mysqli_close($con);
?>
