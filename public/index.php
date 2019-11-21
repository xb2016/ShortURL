<?php
include "..\\config.cfg";

$file = "..\\urls.json";
$url_base = json_decode(file_get_contents($file),true);

if(getParam("id")){
    $id = getParam("id");
    if(array_key_exists($id,$url_base)) $url = $url_base[$id]; else $url = $LOCALURL;
    header("Location: ".$url);
}else{ ?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="format-detection" content="telphone=no,email=no">
    <meta name="keywords" content="loli,萝莉">
    <meta name="description" itemprop="description" content="好耶！是萝莉耶！">
    <link href="<?php echo $RESURL; ?>/favicon.ico" rel="icon" type="image/x-icon">
    <title>loli</title>
    <style>
      *{margin:0;padding:0;font-family:Microsoft YaHei,'\9ed1\4f53'}
      .main{width:80%;margin:0 auto;box-sizing:border-box;padding:0;text-align:center}
      .main p{display:block;font-size:12px;font-weight:300;color:#839ca1}
      .main a{color:#f9be99;text-decoration:none}
    </style>
  </head>
  <body>
    <div class="main">
        <img src="<?php echo $RESURL; ?>/dbeer.jpg" alt=""><br><br>
        <p>© <script>document.write(new Date().getFullYear())</script> <a href="https://loli.cab" rel="nofollow">LOLI</a> . All Rights Received.</p><br><br>
    </div>
  </body>
</html><?php
}