<?php
// Math captcha for login.html: draws "a + b = ?" / "a - b = ?" as a PNG and keeps
// only the answer in the session, so the browser never sees it. login.php checks it.

session_start();

$a = random_int(1, 9);
$b = random_int(1, 9);
if (random_int(0, 1)) {
   $op = '+';
   $answer = $a + $b;
} else {
   // keep subtraction answers positive
   if ($a < $b) {
      [$a, $b] = [$b, $a];
   }
   $op = '-';
   $answer = $a - $b;
}

$_SESSION['captcha_answer'] = $answer;
$_SESSION['captcha_time'] = time();
session_write_close();

$text = "$a $op $b = ?";

// draw with GD's built-in font (9px per glyph) on a small canvas sized to the text,
// then scale up to 150x46 for chunky readable glyphs
$sw = strlen($text) * 9 + 12;
$sh = 24;
$small = imagecreatetruecolor($sw, $sh);
imagefill($small, 0, 0, imagecolorallocate($small, 238, 242, 255));

$x = 6;
for ($i = 0; $i < strlen($text); $i++) {
   $color = imagecolorallocate($small, random_int(20, 90), random_int(20, 70), random_int(120, 200));
   imagechar($small, 5, $x, random_int(2, 7), $text[$i], $color);
   $x += 9;
}

$w = 150;
$h = 46;
$img = imagecreatetruecolor($w, $h);
imagecopyresampled($img, $small, 0, 0, 0, 0, $w, $h, $sw, $sh);

// noise so the text isn't trivially machine-readable
for ($i = 0; $i < 5; $i++) {
   $c = imagecolorallocatealpha($img, random_int(80, 180), random_int(80, 180), random_int(150, 230), 70);
   imagesetthickness($img, random_int(1, 2));
   imageline($img, random_int(0, $w), random_int(0, $h), random_int(0, $w), random_int(0, $h), $c);
}
for ($i = 0; $i < 180; $i++) {
   $c = imagecolorallocatealpha($img, random_int(60, 200), random_int(60, 200), random_int(100, 230), 60);
   imagesetpixel($img, random_int(0, $w - 1), random_int(0, $h - 1), $c);
}

header('Content-Type: image/png');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
imagepng($img);
