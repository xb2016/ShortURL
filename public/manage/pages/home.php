<?php
include "..\\..\\..\\config.cfg";

$admininfo = json_decode(file_get_contents("..\\..\\..\\admininfo.json"),true);
$Admin_Name = $admininfo["user"];
$Admin_Pwd = $admininfo["pwd"];

if($Admin_Pwd != getSession("password") || $Admin_Name != getSession("name")){
    header("Location: ".$LOCALURL."/manage/login.php?ref=".basename(__FILE__,".php"));
    exit;
} ?>
<!DOCTYPE html>
<html class="x-admin-sm">
    <head>
        <meta charset="UTF-8">
        <title>主页</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,user-scalable=yes,minimum-scale=0.4,initial-scale=0.8">
        <link rel="stylesheet" href="<?php echo $RESURL; ?>/manage/static/css/xadmin.min.css">
        <script src="<?php echo $RESURL; ?>/manage/static/lib/layui/layui.js"></script>
    </head>
    <body>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body ">
                            <blockquote class="layui-elem-quote">欢迎管理员：<span class="x-red"><?php echo $admininfo["name"]; ?></span>！当前时间：<span id="current-time"></span></blockquote>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">系统信息</div>
                        <div class="layui-card-body ">
                            <table class="layui-table">
                                <tbody>
                                    <tr>
                                        <th>服务器地址</th>
                                        <td><?php echo $_SERVER["HTTP_HOST"]." [ ".gethostbyname($_SERVER["SERVER_NAME"])." ]"; ?></td></tr>
                                    <tr>
                                        <th>操作系统</th>
                                        <td><?php echo php_uname("s"); ?></td></tr>
                                    <tr>
                                        <th>运行环境</th>
                                        <td><?php echo $_SERVER["SERVER_SOFTWARE"]; ?></td></tr>
                                    <tr>
                                        <th>PHP版本</th>
                                        <td><?php echo PHP_VERSION; ?></td></tr>
                                    <tr>
                                        <th>PHP运行方式</th>
                                        <td><?php echo php_sapi_name(); ?></td></tr>
                                    <tr>
                                        <th>上传附件限制</th>
                                        <td><?php echo ini_get("upload_max_filesize"); ?></td></tr>
                                    <tr>
                                        <th>执行时间限制</th>
                                        <td><?php echo ini_get("max_execution_time")."s"; ?></td></tr>
                                    <tr>
                                        <th>剩余空间</th>
                                        <td><?php echo round((disk_free_space(".")/(1024*1024)),2)."M"; ?></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12">
                    <blockquote class="layui-elem-quote layui-quote-nm" style="background:#fff">&copy; <script>document.write(new Date().getFullYear())</script> <a href="https://prprpr.love" target="_blank">MOEDOG</a>. All Rights Reserved. Powered By X-Admin.</blockquote>
                </div>
            </div>
        </div>
        <script>
        layui.use(["layer","jquery"],function(){
            $ = layui.jquery;
            var layer = layui.layer;
            setInterval(function(){
                var now = (new Date()).toLocaleString();
                $("#current-time").text(now);
            },1000);
        })
        </script>
    </body>
</html>