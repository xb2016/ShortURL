<?php
include "..\\..\\config.cfg";

$admininfo = json_decode(file_get_contents("..\\..\\admininfo.json"),true);
$Admin_Name = $admininfo["user"];
$Admin_Pwd = $admininfo["pwd"];

if($Admin_Pwd != getSession("password") || $Admin_Name != getSession("name")){
    header("Location: ".$LOCALURL."/manage/login.php");
    exit;
} ?>
<!DOCTYPE html>
<html class="x-admin-sm">
    <head>
        <meta charset="UTF-8">
        <title>Short URLs - Manage</title>
        <meta name="renderer" content="webkit|ie-comp|ie-stand">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,user-scalable=yes,minimum-scale=0.4,initial-scale=0.8">
        <meta http-equiv="Cache-Control" content="no-siteapp">
        <link rel="stylesheet" href="<?php echo $RESURL; ?>/manage/static/css/xadmin.min.css">
        <script src="<?php echo $RESURL; ?>/manage/static/lib/layui/layui.js"></script>
        <script src="<?php echo $RESURL; ?>/manage/static/js/xadmin.min.js"></script>
        <link href="<?php echo $RESURL; ?>/manage/static/favicon.ico" rel="shortcut icon">
        <script>var is_remember = false;</script>
    </head>
    <body class="index">
        <div class="container">
            <div class="logo"><a href="<?php echo $LOCALURL; ?>/manage/">Short URLs</a></div>
            <div class="left_open"><a><i title="展开左侧栏" class="iconfont">&#xe699;</i></a></div>
            <ul class="layui-nav right" lay-filter="">
                <li class="layui-nav-item">
                    <a href="javascript:;"><?php echo $admininfo["name"]; ?></a>
                    <dl class="layui-nav-child">
                        <dd><a onclick="xadmin.open('信息修改','<?php echo $LOCALURL; ?>/manage/pages/infoedit.php',320,280)">修改信息</a></dd>
                        <dd><a href="<?php echo $LOCALURL; ?>/manage/login.php?act=logout">退出登录</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item to-index"><a href="<?php echo $LOCALURL; ?>">前台首页</a></li>
            </ul>
        </div>
        <div class="left-nav">
            <div id="side-nav">
                <ul id="nav">
                    <li>
                        <a onclick="xadmin.add_tab('URL 管理','<?php echo $LOCALURL; ?>/manage/pages/urlmanage.php')">
                            <i class="iconfont left-nav-li" lay-tips="URL 管理">&#xe723;</i>
                            <cite>URL 管理</cite>
                        </a>
                    </li>
                    <li>
                        <a onclick="xadmin.open('信息修改','<?php echo $LOCALURL; ?>/manage/pages/infoedit.php',320,280)">
                            <i class="iconfont left-nav-li" lay-tips="系统设置">&#xe6ae;</i>
                            <cite>信息修改</cite>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $LOCALURL; ?>/manage/login.php?act=logout">
                            <i class="iconfont left-nav-li" lay-tips="退出登录">&#xe6b7;</i>
                            <cite>退出登录</cite>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="page-content">
            <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
                <ul class="layui-tab-title">
                    <li class="home layui-this"><i class="layui-icon">&#xe68e;</i>主页</li>
                </ul>
                <div class="layui-unselect layui-form-select layui-form-selected" id="tab_right">
                    <dl>
                        <dd data-type="this">关闭当前</dd>
                        <dd data-type="other">关闭其它</dd>
                        <dd data-type="all">关闭全部</dd>
                    </dl>
                </div>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <iframe src="<?php echo $LOCALURL; ?>/manage/pages/home.php" frameborder="0" scrolling="yes" class="x-iframe"></iframe>
                    </div>
                </div>
                <div id="tab_show"></div>
            </div>
        </div>
        <div class="page-content-bg"></div>
    </body>
</html>