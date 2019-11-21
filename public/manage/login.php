<?php
include "..\\..\\config.cfg";

$admininfo = json_decode(file_get_contents("..\\..\\admininfo.json"),true);
$Admin_Name = $admininfo["user"];
$Admin_Pwd = $admininfo["pwd"];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = test_input($_POST["name"]);
    $password = test_input($_POST["password"]);
    $code = test_input($_POST["code"]);
    if($code != $_SESSION["code"]){
        $error = "验证码错误";
        $_SESSION["code"] = mt_rand(1000,9999);
    }elseif(pwd_encode($password) != $Admin_Pwd || $name != $Admin_Name){
        $error = "用户名或密码错误";
    }else{
        if(session_status() != 2) session_start();
        $_SESSION["name"] = $Admin_Name;
        $_SESSION["password"] = $Admin_Pwd;
        $error = 0;
    }
    header("Content-type: application/json");
    echo json_encode(array("error" => $error));
    exit;
}

if(getParam("ref")){
    $gourl =  $LOCALURL."/manage/pages/".test_input(getParam("ref")).".php";
    if(getParam("type")&&getParam("id")) $gourl .=  "?type=".test_input(getParam("type"))."&id=".test_input(getParam("id"));
}else{
    $gourl = $LOCALURL."/manage/";
}

if(getParam("act") == "logout"){
    $_SESSION = array();
    if(isset($_COOKIE[session_name()])) setcookie(session_name(),'',time()-42000,'/');
    session_destroy();
    header("Location: ".$LOCALURL."/manage/login.php");
}elseif($Admin_Pwd == getSession("password") && $Admin_Name == getSession("name")){
    header("Location: ".$gourl);
}else{ ?>
<!doctype html>
<html class="x-admin-sm">
    <head>
        <meta charset="UTF-8">
        <title>Short URLs - Login</title>
        <meta name="renderer" content="webkit|ie-comp|ie-stand">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,user-scalable=yes,minimum-scale=0.4,initial-scale=0.8">
        <meta http-equiv="Cache-Control" content="no-siteapp">
        <link rel="stylesheet" href="<?php echo $RESURL; ?>/manage/static/css/xadmin.min.css">
        <script src="<?php echo $RESURL; ?>/manage/static/lib/layui/layui.js"></script>
        <link href="<?php echo $RESURL; ?>/manage/static/favicon.ico" rel="shortcut icon">
    </head>
    <body id="login">
        <div class="login-bg"></div>
        <div class="login layui-anim layui-anim-up">
            <div class="message">用户登录</div>
            <div id="darkbannerwrap"></div>
            <form class="layui-form">
                <input name="name" autocomplete="off" placeholder="用户名" type="text" lay-verify="required" class="layui-input">
                <hr class="hr15">
                <input name="password" placeholder="密码" type="password" lay-verify="required" class="layui-input">
                <hr class="hr15">
                <input name="code" autocomplete="off" placeholder="验证码" type="text" lay-verify="required" class="layui-inline">
                <img id="captcha" alt="点击刷新" title="点击刷新" src="<?php echo $LOCALURL; ?>/manage/pages/captcha.php">
                <hr class="hr15">
                <button lay-submit lay-filter="login" type="submit">登录</button>
                <hr class="hr20">
            </form>
        </div>
        <script>
        layui.use(["form","jquery"],function(){
            $ = layui.jquery;
            var form = layui.form,layer = layui.layer;
            form.on("submit(login)",function(){
                layer.msg("正在登录");
                $.ajax({
                    type:"POST",
                    dataType:"json",
                    url:"<?php echo $LOCALURL; ?>/manage/login.php",
                    data:$("form").serialize(),
                    success:function(d){
                        if(d.error == "0"){
                            layer.msg("登录成功，正在跳转");
                            window.location.href = "<?php echo $gourl; ?>"
                        }else{
                            $("input").val("");
                            layer.msg(d.error);
                            getcode()
                        }
                    },
                    error:function(){layer.msg("与服务器通信时发生错误，请稍后重试",{anim:6})}
                });
                return false
            });
            $(document).on("click","#captcha",function(){getcode()});
            function getcode(){$("#captcha").attr("src","<?php echo $LOCALURL; ?>/manage/pages/captcha.php?"+ +new Date())}
        })
        </script>
    </body>
</html><?php
}