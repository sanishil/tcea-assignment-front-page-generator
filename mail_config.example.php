<?php
// SMTP settings for password-reset emails (forgot_password.php).
// Copy this file to mail_config.php and fill it in. mail_config.php is ignored by git
// so the password never ends up in the repository.
//
// Gmail: turn on 2-Step Verification, then create an App Password at
// https://myaccount.google.com/apppasswords and paste the 16 letters below (not your normal password).
return [
   'host'       => 'smtp.gmail.com',
   'port'       => 587,
   'secure'     => 'tls',              // 'tls' (port 587), 'ssl' (port 465) or '' (no encryption)
   'username'   => 'educoverpage.helpdesk@gmail.com',
   'password'   => 'rahkbugrcjahmcai',
   'from_email' => 'educoverpage.helpdesk@gmail.com',
   'from_name'  => 'TCEA EduCover',
];
