<?php
include "..\\..\\..\\config.cfg";

$admininfo = json_decode(file_get_contents("..\\..\\..\\admininfo.json"),true);
$Admin_Name = $admininfo["user"];
$Admin_Pwd = $admininfo["pwd"];

if($Admin_Pwd != getSession("password") || $Admin_Name != getSession("name")){
    header("Location: ".$LOCALURL."/manage/login.php?ref=".basename(__FILE__,".php")."&type=".test_input(getParam("type"))."&id=".test_input(getParam("id")));
    exit;
} ?>
<!DOCTYPE html>
<html class="x-admin-sm">
    <head>
        <meta charset="UTF-8">
        <title>信息修改</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,user-scalable=yes,minimum-scale=0.4,initial-scale=0.8">
        <link rel="stylesheet" href="<?php echo $RESURL; ?>/manage/static/css/xadmin.min.css">
        <script src="<?php echo $RESURL; ?>/manage/static/lib/layui/layui.js"></script>
    </head>
    <body>
        <div class="layui-fluid">
            <div class="layui-row">
                <form class="layui-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label" style="width:40px;padding:5px">昵称</label>
                        <div class="layui-input-inline" style="margin:0 10px 0 60px">
                            <input type="text" placeholder="" id="name" name="name" autocomplete="off" value="<?php echo $admininfo["name"]; ?>" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label" style="width:40px;padding:5px">用户名</label>
                        <div class="layui-input-inline" style="margin:0 10px 0 60px">
                            <input type="text" placeholder="" id="user" name="user" autocomplete="off" value="<?php echo $admininfo["user"]; ?>" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label" style="width:40px;padding:5px">旧密码</label>
                        <div class="layui-input-inline" style="margin:0 10px 0 60px">
                            <input type="password" placeholder="" id="pwdold" name="pwdold" autocomplete="off" class="data layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label" style="width:40px;padding:5px">新密码</label>
                        <div class="layui-input-inline" style="margin:0 10px 0 60px">
                            <input type="text" placeholder="" id="pwd" name="pwd" autocomplete="off" class="data layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item" style="margin-bottom:0">
                        <button class="layui-btn" style="position:absolute;right:30px" lay-filter="submit" lay-submit="">确认</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
        layui.use(["form","layer","jquery"],function(){
            $ = layui.jquery;
            var form = layui.form,layer = layui.layer;
            form.on("submit(submit)",function(){
                $(".layui-btn").attr("disabled",true);
                $.ajax({
                    type:"POST",
                    dataType:"json",
                    url:"<?php echo $LOCALURL; ?>/manage/admin_api/",
                    data:{act:"infoedit",name:$("#name").val(),user:$("#user").val(),pwdold:$("#pwdold").val(),pwd:$("#pwd").val()},
                    success:function(d){
                        if(d.error=="0"){
                            $("input.data").val("");
                            layer.msg("修改成功",{icon:1,time:1250},function(){parent.location.reload()})
                        }else layer.msg(d.error,{icon:2})
                    },
                    error:function(){layer.msg("与服务器通信时发生错误，请稍后重试",{anim:6})},
                    complete:function(){$(".layui-btn").attr("disabled",false)}
                });
                return false
            })
        })
        </script>
    </body>
</html>