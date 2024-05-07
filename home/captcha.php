<?php

/**
 * Author: Möf Selvi
 * Simple captcha script in PHP.
 * Based on much simpler captcha examples. A higher entropy is implemented.
 * Licensed under MIT.
 * 
 * @author      Möf Selvi (@mofthedev)
 * @copyright   Möf Selvi (Muhammed Ömer Faruk Selvi, mofselvi)
 * @license     http://opensource.org/licenses/MIT MIT License
 */


$font_files = ["arial.ttf","sewer.ttf","sixty.ttf"];


$fontfile = (dirname(__FILE__))."/".$font_files[array_rand($font_files)];


$code_len = 8;


/**
 * Returns random SECURE string with length $len based on base58 characters.
 * 
 * @return string
 */
function get_rand_str($len=6, $charset = "")
{
    //$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $chars = '123456789ABCDEFGHJKLMNPQRSTUVWXYZ';//base58 chars
    if(!empty($charset))
    {
        $chars = $charset;
    }
    $chr_num = strlen($chars);
    $str='';
    $get_num=0;
        for ($r = 0; $r<$len; $r++)
        {
            $get_num = random_int(0,$chr_num-1);
            $str .= $chars[$get_num];
        }
    return $str;
}


session_start();
$code = get_rand_str($code_len);

$img_h = 100;
$img_w = $code_len*50;


$captcha = $code;
$_SESSION["captcha"]= strtoupper($captcha);


$charsize = 5;

$img=imagecreate($img_w, $img_h);//imagecreatetruecolor(90,40);
$bg = imagecolorallocate($img, rand(240,255), rand(240,255), rand(240,255));


for($capti=0; $capti<$code_len; $capti++)
{
    $fontfile = (dirname(__FILE__))."/".$font_files[array_rand($font_files)];
    $color0=imagecolorallocate($img,rand(10,200),rand(10,200),rand(10,200));
    imagettftext($img, rand($img_w/$code_len - 10, $img_h/$code_len + 10), rand(-50, 50), ($capti+0.2)*$img_w/$code_len, $img_h/1.5, $color0, $fontfile, $code[$capti]." ");
}





$grid_color = imagecolorallocate($img, rand(0,255), rand(0,255), rand(0,255));
$number_to_loop = ceil($img_w / 10);
for($i = 0; $i < $number_to_loop; $i++)
{
    $grid_color = imagecolorallocate($img, rand(0,255), rand(0,255), rand(0,255));
    $x = ($i + 1) * 10;
    imageline($img, $x, 0, $x+rand(-15,15), $img_h, $grid_color);
}

$grid_color2 = imagecolorallocate($img, rand(0,255), rand(0,255), rand(0,255));
$number_to_loop = ceil($img_h / 10);
for($i = 0; $i < $number_to_loop; $i++)
{
    $grid_color2 = imagecolorallocate($img, rand(0,255), rand(0,255), rand(0,255));
    $y = ($i + 1) * 10;
    imageline($img, 0, $y, $img_w, $y+rand(-15,15), $grid_color2);
}



$line_color = imagecolorallocate($img, rand(0,255), rand(0,255), rand(0,255));
for($i = 0; $i < 5; $i++)
{
    $line_color = imagecolorallocate($img, rand(0,255), rand(0,255), rand(0,255));
    $rand_x_1 = rand(0, $img_w - 1);
    $rand_x_2 = rand(0, $img_w - 1);
    $rand_y_1 = rand(0, $img_h - 1);
    $rand_y_2 = rand(0, $img_h - 1);
    imageline($img, $rand_x_1, $rand_y_1, $rand_x_2, $rand_y_2, $line_color);
}


header('Content-type: image/png');
imagepng($img);
imagedestroy($img);
?>