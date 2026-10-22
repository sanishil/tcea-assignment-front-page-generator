<?php

error_reporting(0);
session_start();

// math captcha from captcha.php: single use, valid for 5 minutes
$captcha_ok = isset($_SESSION['captcha_answer'], $_POST['captcha'])
  && time() - $_SESSION['captcha_time'] <= 300
  && preg_match('/^\d+$/', trim($_POST['captcha']))
  && (int) trim($_POST['captcha']) === $_SESSION['captcha_answer'];
unset($_SESSION['captcha_answer'], $_SESSION['captcha_time']);
if (!$captcha_ok) {
  session_write_close();
  echo "<script>window.location.href='login.html?error=captcha';</script>";
  exit;
}

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
    echo "<script>window.location.href='login.html?error=active';</script>";
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
  echo "<script>window.location.href='login.html?error=mismatch';</script>";
}
mysqli_close($con);
?>