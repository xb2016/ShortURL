<?php
if(session_status() != 2) session_start();

//创建 80*30 白色图片
$image = imagecreatetruecolor(80,30);
$bgcolor = imagecolorallocate($image,255,255,255);
imagefill($image,0,0,$bgcolor);

//生成随机数
$code = "";
for($i=0;$i<4;$i++){
    $fontsize = 6;
    $fontcolor = imagecolorallocate($image,rand(0,120),rand(0,120),rand(0,120));
    $fontcontent = mt_rand(0,9);
    $code .= $fontcontent;
    $x = ($i*80/4) + mt_rand(5,10);
    $y = mt_rand(5,10);
    imagestring($image,$fontsize,$x,$y,$fontcontent,$fontcolor);
}
$_SESSION["code"] = $code;

//生成干扰点
for($i=0;$i<100;$i++){
    $pointcolor = imagecolorallocate($image,rand(50,120),rand(50,120),rand(50,120));
    imagesetpixel($image,rand(1,79),rand(1,29),$pointcolor);
}

//生成干扰线
for($i=0;$i<5;$i++){
    $linecolor = imagecolorallocate($image,rand(80,220),rand(80,220),rand(80,220));
    imageline($image,rand(1,79),rand(1,29),rand(1,79),rand(1,29),$linecolor);
}

//输出图片
header("Content-type: image/png");
imagepng($image);
imagedestroy($image);