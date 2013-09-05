<?php

$root = '.';

include_once "$root/config.php";
include_once "$root/common.php";

$key = ( isset($_GET['k']) ) ? strtolower( $_GET['k'] ) : 'default';

define('CAPTCHA_WIDTH', 150);
define('CAPTCHA_HEIGHT', 50);

/**
 * Captcha case INsensitive.
 */
$captcha = strtolower( Session::captcha($key, String::random(CAPTCHA_LENGTH)) );

$image  = imagecreate(CAPTCHA_WIDTH, CAPTCHA_HEIGHT);
          imagecolorallocate($image, 255, 255, 255);

$color  = imagecolorallocate($image, mt_rand(0, 180), mt_rand(0, 180), mt_rand(0, 180));
$border = imagecolorallocate($image, 210, 210, 210);
$shadow = imagecolorallocate($image, 180, 180, 180);

for ( $i = 0, $sup = ( CAPTCHA_WIDTH * CAPTCHA_HEIGHT ) / CAPTCHA_LENGTH; $i < $sup; $i++ )
    imagesetpixel($image, mt_rand(0, CAPTCHA_WIDTH), mt_rand(0, CAPTCHA_HEIGHT), $color);

imagerectangle($image, 0, 0, CAPTCHA_WIDTH - 1, CAPTCHA_HEIGHT - 1, $border);

$angle = mt_rand(-3, 3);

imagettftext($image, 25, $angle, 12, 42, $shadow, "$root/themes/$theme/captcha.ttf", $captcha);
imagettftext($image, 25, $angle, 10, 40, $color, "$root/themes/$theme/captcha.ttf", $captcha);

header('Content-Type: image/jpeg');

imagejpeg($image);
imagedestroy($image);