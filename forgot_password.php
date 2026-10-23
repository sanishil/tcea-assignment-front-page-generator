<?php
// Forgot password, called from login.html with JSON { op: ... }
//   send_otp  { email }        email a 6-digit code to the account whose login ID is that email
//   verify_otp { otp }         check the code; on success the session may set a new password
//   reset     { pass, cpass }  save the new password (only after verify_otp succeeded)
// The code itself is only stored hashed, in the password_otp table.

header("Content-Type: application/json");
error_reporting(0);
session_start();

define('OTP_TTL', 600);          // code is valid for 10 minutes
define('OTP_MAX_TRIES', 5);      // wrong guesses allowed per code
define('OTP_RESEND_WAIT', 60);   // seconds before another code can be requested
define('OTP_PER_HOUR', 5);       // codes per account per hour
define('RESET_WINDOW', 600);     // time to choose a new password after the code is accepted
define('MIN_PASS_LEN', 6);

include 'tech/api/connect.php';

function reply($status, $message, $extra = [])
{
   echo json_encode(array_merge(["status" => $status, "message" => $message], $extra));
   exit;
}

$con->query("CREATE TABLE IF NOT EXISTS password_otp (
   id INT AUTO_INCREMENT PRIMARY KEY,
   user_id VARCHAR(100) NOT NULL,
   otp_hash VARCHAR(255) NOT NULL,
   attempts TINYINT NOT NULL DEFAULT 0,
   used TINYINT(1) NOT NULL DEFAULT 0,
   expires_at DATETIME NOT NULL,
   created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
   INDEX (user_id)
)");

$input = json_decode(file_get_contents("php://input"), true) ?: [];
$op = $input['op'] ?? '';

// ---------------------------------------------------------------- send code
if ($op === 'send_otp') {
   $email = trim($input['email'] ?? '');
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      reply("error", "Please enter a valid email address.");
   }

   $stmt = $con->prepare("SELECT user_id, name FROM login WHERE user_id = ?");
   $stmt->bind_param("s", $email);
   $stmt->execute();
   $user = $stmt->get_result()->fetch_assoc();
   if (!$user) {
      reply("error", "This user is not valid or does not exist.");
   }

   // throttle: one code per minute, a few per hour
   $stmt = $con->prepare("SELECT
         SUM(created_at >= NOW() - INTERVAL 1 HOUR) AS last_hour,
         MAX(TIMESTAMPDIFF(SECOND, created_at, NOW()) < ?) AS too_soon,
         ? - MIN(TIMESTAMPDIFF(SECOND, created_at, NOW())) AS wait
      FROM password_otp WHERE user_id = ?");
   $wait = OTP_RESEND_WAIT;
   $stmt->bind_param("iis", $wait, $wait, $email);
   $stmt->execute();
   $recent = $stmt->get_result()->fetch_assoc();
   if ($recent['too_soon']) {
      reply("error", "Please wait " . max(1, (int) $recent['wait']) . " seconds before requesting a new code.", ["wait" => (int) $recent['wait']]);
   }
   if ((int) $recent['last_hour'] >= OTP_PER_HOUR) {
      reply("error", "Too many codes requested for this account. Please try again in an hour.");
   }

   $config = @include 'mail_config.php';
   if (!is_array($config) || empty($config['host']) || empty($config['from_email'])) {
      reply("error", "Password reset email is not set up yet. Please contact the administrator.");
   }

   $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
   $hash = password_hash($otp, PASSWORD_DEFAULT);

   // only the newest code works
   $stmt = $con->prepare("UPDATE password_otp SET used = 1 WHERE user_id = ? AND used = 0");
   $stmt->bind_param("s", $email);
   $stmt->execute();

   $ttl = OTP_TTL;
   $stmt = $con->prepare("INSERT INTO password_otp (user_id, otp_hash, expires_at) VALUES (?, ?, NOW() + INTERVAL ? SECOND)");
   $stmt->bind_param("ssi", $email, $hash, $ttl);
   $stmt->execute();
   $otp_id = $stmt->insert_id;

   require 'lib/PHPMailer/Exception.php';
   require 'lib/PHPMailer/PHPMailer.php';
   require 'lib/PHPMailer/SMTP.php';

   $mail = new PHPMailer\PHPMailer\PHPMailer(true);
   try {
      $mail->isSMTP();
      $mail->Host = $config['host'];
      $mail->Port = (int) $config['port'];
      $mail->SMTPAuth = $config['username'] !== '';
      $mail->Username = $config['username'];
      $mail->Password = $config['password'];
      if ($config['secure'] === 'ssl') {
         $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
      } elseif ($config['secure'] === 'tls') {
         $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
      } else {
         $mail->SMTPAutoTLS = false;
      }
      $mail->CharSet = 'UTF-8';
      // look like a normal mail from the sender's domain, not "localhost" + "X-Mailer: PHPMailer"
      $mail->Hostname = substr(strrchr($config['from_email'], '@'), 1);
      $mail->XMailer = ' ';
      $mail->setFrom($config['from_email'], $config['from_name'] ?? 'TCEA');
      $mail->addReplyTo($config['from_email'], $config['from_name'] ?? 'TCEA');
      $mail->addAddress($email, $user['name'] ?? '');
      $mail->isHTML(true);
      $mail->Subject = "Password reset code - Techno College of Engineering Agartala";

      $name = htmlspecialchars(ucwords($user['name'] ?? ''));
      $minutes = OTP_TTL / 60;
      $mail->Body = "
         <div style=\"font-family:Arial,Helvetica,sans-serif;max-width:480px;margin:0 auto;padding:24px;color:#1f2937\">
            <h2 style=\"margin:0 0 12px;color:#347ec4\">Techno College of Engineering Agartala</h2>
            <p>Hello $name,</p>
            <p>Use this code to reset your password:</p>
            <p style=\"font-size:32px;font-weight:bold;letter-spacing:8px;margin:16px 0;color:#4f46e5\">$otp</p>
            <p>The code expires in $minutes minutes and can be used once.</p>
            <p style=\"color:#6b7280;font-size:13px\">If you did not ask to reset your password, you can ignore this email. Your password will not change.</p>
         </div>";
      $mail->AltBody = "Your password reset code is $otp. It expires in $minutes minutes. "
         . "If you did not ask to reset your password, ignore this email.";
      $mail->send();
   } catch (Exception $e) {
      error_log("forgot_password: mail to $email failed: " . $mail->ErrorInfo);
      $stmt = $con->prepare("DELETE FROM password_otp WHERE id = ?");
      $stmt->bind_param("i", $otp_id);
      $stmt->execute();
      reply("error", "We could not send the email right now. Please try again later.");
   }

   session_regenerate_id(true);
   $_SESSION['fp_user'] = $email;
   unset($_SESSION['fp_verified_at']);

   // s****@gmail.com
   [$local, $domain] = explode('@', $email, 2);
   $masked = substr($local, 0, 1) . str_repeat('*', max(3, strlen($local) - 1)) . '@' . $domain;
   reply("success", "We sent a 6-digit code to $masked.", ["masked" => $masked, "wait" => OTP_RESEND_WAIT]);
}

// ---------------------------------------------------------------- check code
if ($op === 'verify_otp') {
   $email = $_SESSION['fp_user'] ?? '';
   $otp = trim($input['otp'] ?? '');
   if ($email === '') {
      reply("error", "Your session has expired. Please start again.", ["restart" => true]);
   }
   if (!preg_match('/^\d{6}$/', $otp)) {
      reply("error", "Enter the 6-digit code from the email.");
   }

   $stmt = $con->prepare("SELECT id, otp_hash, attempts, expires_at < NOW() AS expired
      FROM password_otp WHERE user_id = ? AND used = 0 ORDER BY id DESC LIMIT 1");
   $stmt->bind_param("s", $email);
   $stmt->execute();
   $row = $stmt->get_result()->fetch_assoc();

   if (!$row || $row['expired']) {
      reply("error", "This code has expired. Please request a new one.");
   }
   if ($row['attempts'] >= OTP_MAX_TRIES) {
      reply("error", "Too many wrong attempts. Please request a new code.");
   }

   if (!password_verify($otp, $row['otp_hash'])) {
      $stmt = $con->prepare("UPDATE password_otp SET attempts = attempts + 1 WHERE id = ?");
      $stmt->bind_param("i", $row['id']);
      $stmt->execute();
      $left = OTP_MAX_TRIES - $row['attempts'] - 1;
      reply("error", $left > 0
         ? "That code is not correct. $left " . ($left == 1 ? "attempt" : "attempts") . " left."
         : "Too many wrong attempts. Please request a new code.");
   }

   $stmt = $con->prepare("UPDATE password_otp SET used = 1 WHERE id = ?");
   $stmt->bind_param("i", $row['id']);
   $stmt->execute();

   $_SESSION['fp_verified_at'] = time();
   reply("success", "Code verified. Choose a new password.");
}

// ---------------------------------------------------------------- new password
if ($op === 'reset') {
   $email = $_SESSION['fp_user'] ?? '';
   $verified = $_SESSION['fp_verified_at'] ?? 0;
   if ($email === '' || !$verified || time() - $verified > RESET_WINDOW) {
      unset($_SESSION['fp_user'], $_SESSION['fp_verified_at']);
      reply("error", "Your reset session has expired. Please start again.", ["restart" => true]);
   }

   $pass = $input['pass'] ?? '';
   $cpass = $input['cpass'] ?? '';
   if (strlen($pass) < MIN_PASS_LEN) {
      reply("error", "Password must be at least " . MIN_PASS_LEN . " characters.");
   }
   if ($pass !== $cpass) {
      reply("error", "The two passwords do not match.");
   }

   // stored the same way login.php and change_pass.php compare it; token=0 also signs out any open session
   $stmt = $con->prepare("UPDATE login SET pass = ?, token = 0 WHERE user_id = ?");
   $stmt->bind_param("ss", $pass, $email);
   if (!$stmt->execute()) {
      reply("error", "Could not update the password. Please try again.");
   }

   unset($_SESSION['fp_user'], $_SESSION['fp_verified_at']);
   reply("success", "Your password is updated.");
}

reply("error", "Invalid request.");
