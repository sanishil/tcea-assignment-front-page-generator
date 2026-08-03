<?php 
session_start();
include 'tech/api/connect.php';
$user_id=$_SESSION['user_id'];
$sql_token = "UPDATE login SET token=0 WHERE user_id='" . $user_id . "'";
$res2 = mysqli_query($con, $sql_token) or die(mysqli_error($con));
session_unset();
session_destroy();

header("Location: login.html");
exit;
?>