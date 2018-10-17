<?php
session_start();
$text = rand(1,99);

$_SESSION["vecode"] = $text;

$height = 18;

$width = 18;



$image_p = imagecreate($width, $height);

$white = imagecolorallocate($image_p, 255, 255, 255);

$black = imagecolorallocate($image_p, 0, 0, 0);

$font_size = 4;



imagestring($image_p, $font_size, 0, 0, $text, $black);

imagejpeg($image_p, null, 100, $white);

?>