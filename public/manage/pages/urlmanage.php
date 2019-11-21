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
        <title>URL 管理</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,user-scalable=yes,minimum-scale=0.4,initial-scale=0.8">
        <link rel="stylesheet" href="<?php echo $RESURL; ?>/manage/static/css/xadmin.min.css">
        <script src="<?php echo $RESURL; ?>/manage/static/lib/layui/layui.js"></script>
        <script src="<?php echo $RESURL; ?>/manage/static/js/xadmin.min.js"></script>
    </head>
    <body>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body ">
                            <div class="layui-form layui-col-space5">
                                <div class="layui-inline" style="max-width:50%">
                                    <input class="layui-input data" autocomplete="off" placeholder="搜索 URL" name="search" id="search">
                                </div>
                                <div class="layui-inline">
                                    <button class="layui-btn btn" id="searchbtn" title="搜索"><i class="layui-icon">&#xe615;</i></button>
                                </div>
                                <div class="layui-inline" style="float:right">
                                    <button class="layui-btn btn" id="refresh" title="刷新"><i class="layui-icon">&#xe669;</i></button>
                                </div>
                            </div>
                        </div>
                        <div class="layui-card-body ">
                            <table class="layui-table" lay-data="{height:'full-120', url:'<?php echo $LOCALURL; ?>/manage/admin_api/?act=getkey&id=<?php echo getParam("id"); ?>', toolbar:'#toolbarDemo', id:'key'}" lay-filter="key">
                                <thead>
                                    <tr>
                                        <th lay-data="{type:'checkbox', width:80}"></th>
                                        <th lay-data="{field:'id', sort:true, minWidth:80}">ID</th>
                                        <th lay-data="{field:'key', sort:true, edit:'text', minWidth:80}">KEY</th>
                                        <th lay-data="{field:'num', sort:true, edit:'text', minWidth:400}">URL</th>
                                        <th lay-data="{field:'url', minWidth:300}">SHORT URL</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <div style="display:none" id="toolbarDemo">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-danger" lay-event="DelKey"><i class="layui-icon">&#xe640;</i>删除</button>
            <button class="layui-btn" lay-event="AddKey"><i class="layui-icon">&#xe608;</i>添加</button></button>
        </div> 
    </div>
    <script>
    layui.use(["table","layer","jquery"],function(){
        $ = layui.jquery;
        var table = layui.table,layer = layui.layer;
        table.on("edit(key)",function(obj){
            var value = obj.value,data = obj.data,field = obj.field,prvdata = $(this).prev().text();
            if(field=="key") var apiact = "changekey&oldkey="+prvdata+"&newkey="+value; else if(field=="num") var apiact = "changenum&oldkey="+data.key+"&newnum="+value;
            $.ajax({
                type:"GET",
                dataType:"json",
                url:"<?php echo $LOCALURL; ?>/manage/admin_api/?id=<?php echo getParam("id"); ?>&act="+apiact,
                success:function(d){
                    if(d.error=="0"){
                        layer.msg("修改成功",{icon:1});
                        if(field=="key") obj.update({url:"<?php echo $LOCALURL; ?>/"+value})
                    }else{
                        layer.msg(d.error,{icon:2});
                        if(field=="key") obj.update({key:prvdata}); else if(field=="num") obj.update({num:prvdata})
                    }
                },
                error:function(){layer.msg("与服务器通信时发生错误，请稍后重试",{anim:6})}
            })
        });
        table.on("toolbar(key)",function(obj){
            var checkStatus = table.checkStatus(obj.config.id);
            switch(obj.event){
                case "DelKey":
                    var data = checkStatus.data,keys = "";
                    for(var i in data) keys += data[i].key+",";
                    keys = keys.substr(0,keys.length-1);
                    if(keys!=""){
                        layer.confirm("确认删除选中的 "+data.length+" 条数据？删除后无法恢复",{title:"删除确认"},function(){
                            $.ajax({
                                type:"POST",
                                dataType:"json",
                                url:"<?php echo $LOCALURL; ?>/manage/admin_api/",
                                data:{id:"<?php echo getParam("id"); ?>",act:"delkey",keylist:keys},
                                success:function(e){
                                    if(e.error=="0"){
                                        layer.msg("删除成功",{icon:1});
                                        table.reload("key")
                                    }else layer.msg(e.error,{icon:2})
                                },
                                error:function(){layer.msg("与服务器通信时发生错误，请稍后重试",{anim:6})}
                            })
                        })
                    }else layer.msg("未选中任何数据",{icon:2});
                    break;
                case "AddKey":
                    xadmin.open("添加 URL","<?php echo $LOCALURL; ?>/manage/pages/addurl.php",300,200);
                    break;
            }
        });
        $(document).on("click","#searchbtn",function(){
            table.reload("key",{url:"<?php echo $LOCALURL; ?>/manage/admin_api/?act=getkey&id=<?php echo getParam("id"); ?>&search="+$("#search").val()})
        });
        $(document).on("click","#refresh",function(){
            table.reload("key",{url:"<?php echo $LOCALURL; ?>/manage/admin_api/?act=getkey&id=<?php echo getParam("id"); ?>"});
            $("input.data").val("")
        })
    })
    </script>
</html>