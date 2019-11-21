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
        <title>添加 URL</title>
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
                        <label class="layui-form-label" style="width:50px;padding:5px">KEY</label>
                        <div class="layui-input-inline" style="margin:0 10px 0 70px">
                            <input type="text" id="key" name="key" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label" style="width:50px;padding:5px">链接</label>
                        <div class="layui-input-inline" style="margin:0 10px 0 70px">
                            <input type="text" id="num" name="num" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item" style="margin-bottom:0">
                        <button class="layui-btn" style="position:absolute;right:30px" lay-filter="add" lay-submit="">添加</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
        layui.use(["form","layer","jquery"],function(){
            $ = layui.jquery;
            var form = layui.form,layer = layui.layer;
            form.on("submit(add)",function(){
                $(".layui-btn").attr("disabled",true);
                $.ajax({
                    type:"POST",
                    dataType:"json",
                    url:"<?php echo $LOCALURL; ?>/manage/admin_api/",
                    data:{id:"<?php echo getParam("id"); ?>",act:"addkey",key:$("#key").val(),num:$("#num").val()},
                    success:function(d){
                        if(d.error=="0"){
                            $("input").val("");
                            layer.msg("添加成功",{icon:1});
                            parent.layui.table.reload("key")
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