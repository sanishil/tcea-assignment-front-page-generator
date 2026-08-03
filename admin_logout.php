<?php 
session_start();
include 'tech/api/connect.php';
$sql_token = "UPDATE login SET token=0 WHERE user_id='sanishil.cse@gmail.com'";
$res2 = mysqli_query($con, $sql_token) or die(mysqli_error($con));
echo "<script>alert('Sani Logout');</script>";
session_unset();
session_destroy();
exit;
?>