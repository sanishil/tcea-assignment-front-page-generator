<?php

error_reporting(0);
session_start();
include 'tech/api/connect.php';
$user_id = mysqli_real_escape_string($con, $_POST['user']);
$pass = mysqli_real_escape_string($con, $_POST['pass']);
// is_active = 1 only if logged in AND active within the last 10 minutes,
// so a session left open (user forgot to logout) expires automatically
$sql = "select *, (token=1 and last_activity >= NOW() - INTERVAL 10 MINUTE) as is_active from login where user_id='" . $user_id . "' and pass='" . $pass . "'";
$res = mysqli_query($con, $sql) or die(mysqli_error($con));
$c = mysqli_num_rows($res);
if ($c > 0) {
  while ($row = mysqli_fetch_array($res)) {
    $type = $row['type'];
    $name = $row['name'];
    $is_active = $row['is_active'];
  }
  if ($is_active == 1) {
    echo "<script>alert('You are already logged in on another device. Logout there or wait 10 minutes. 🚫👨‍💻');</script>";
    echo "<script>window.location.href='login.html';</script>";
  } else {
    if ($type == 'Admin') {
      $_SESSION['user_id'] = $user_id;
      $_SESSION['type'] = $type;
      $_SESSION['name'] = $name;
      $_SESSION['last_activity'] = time();
      $sql_token = "UPDATE login SET token=1, last_activity=NOW() WHERE user_id='" . $user_id . "'";
      $res2 = mysqli_query($con, $sql_token) or die(mysqli_error($con));
      session_write_close();
      echo "<script>window.location.href='admin';</script>";
    } else if ($type == 'Student') {
      $_SESSION['user_id'] = $user_id;
      $_SESSION['type'] = $type;
      $_SESSION['name'] = $name;
      $_SESSION['last_activity'] = time();
      $sql_token = "UPDATE login SET token=1, last_activity=NOW() WHERE user_id='" . $user_id . "'";
      $res2 = mysqli_query($con, $sql_token) or die(mysqli_error($con));
      // session_write_close();
      echo "<script>window.location.href='tech';</script>";
    } else {
      // $_SESSION['user']=$user;
      // $_SESSION['type']=$type;
      // $_SESSION['name']=$name;
      // session_write_close();
      // echo "<script>window.location.href='../../students';</script>";
    }
  }

} else {
  $_SESSION['type'] = "";
  session_write_close();
  echo "<script>alert('Sorry Userid and Password Mismatch');</script>";
  echo "<script>window.location.href='login.html';</script>";
}
mysqli_close($con);
?>