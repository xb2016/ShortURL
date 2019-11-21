<?php
include "..\\..\\..\\config.cfg";

$admininfo = json_decode(file_get_contents("..\\..\\..\\admininfo.json"),true);
$Admin_Name = $admininfo["user"];
$Admin_Pwd = $admininfo["pwd"];

if($Admin_Pwd != getSession("password") || $Admin_Name != getSession("name")){
    header("Content-type: application/json");
    echo json_encode(array("error" =>"Forbidden"));
    exit;
}

//POST
if($_SERVER["REQUEST_METHOD"] == "POST"){
    switch($_POST["act"]){
        case "addkey":
            $error = 0;
            $file = "..\\..\\..\\urls.json";
            $key = test_input($_POST["key"]); $num = test_input($_POST["num"]);
            if($key == NULL) $key = get_new_key();
            $keyjson = json_decode(file_get_contents($file),true);
            if(array_key_exists($key,$keyjson)) $error = "重复的 KEY 值";
            if($error === 0){
                $keyjson[$key] = $num;
                file_put_contents($file,json_encode($keyjson));
            }
            header("Content-type: application/json");
            echo json_encode(array("error" => $error));
            break;

        case "delkey":
            $error = 0;
            $file = "..\\..\\..\\urls.json";
            $keylist = explode(",",test_input($_POST["keylist"]));
            if($keylist == NULL){
                $error = "无效的 KEY 值";
            }else{
                $keyjson = json_decode(file_get_contents($file),true);
                foreach($keylist as $key){
                    if(array_key_exists($key,$keyjson)) unset($keyjson[$key]); else $error = "部分数据处理失败";
                }
                file_put_contents($file,json_encode($keyjson));
            }
            header("Content-type: application/json");
            echo json_encode(array("error" => $error));
            break;

        case "infoedit":
            $New_Name = test_input($_POST["name"]); $New_User = test_input($_POST["user"]);
            $Old_Pwd = test_input($_POST["pwdold"]); $New_Pwd = test_input($_POST["pwd"]);
            if(!$Old_Pwd){
                $error = "旧密码是必须的";
            }elseif($admininfo["pwd"] !== pwd_encode($Old_Pwd)){
                $error = "旧密码错误";
            }else{
                if($New_Pwd) $admininfo["pwd"] = pwd_encode($New_Pwd);
                $admininfo["user"] = $New_User;
                $admininfo["name"] = $New_Name;
                file_put_contents("..\\..\\..\\admininfo.json",json_encode($admininfo));
                $error = 0;
            }
            header("Content-type: application/json");
            echo json_encode(array("error" => $error));
            break;

        default:
            header("Content-type: application/json");
            echo json_encode(array("error" => "Bad Request"));
    }
    exit;
}

//GET
switch(getParam("act")){
    case "getkey":
        $file = "..\\..\\..\\urls.json";
        $searchkey = test_input(getParam("search"));
            $keyjson = json_decode(file_get_contents($file),true);
            $num = 0;
            $keys = [];
            if($searchkey){
                foreach(array_keys($keyjson) as $key){
                    if(strstr($keyjson[$key],$searchkey)) $keys[] = array("id"=>$num+1,"key"=>$key,"num"=>$keyjson[$key],"url"=>$LOCALURL."/".$key);
                    $num ++;
                }
            }else{
                foreach(array_keys($keyjson) as $key){
                    $keys[] = array("id"=>$num+1,"key"=>$key,"num"=>$keyjson[$key],"url"=>$LOCALURL."/".$key);
                    $num ++;
                }
            }
            $data = array("code"=>0,"msg"=>"","count"=>$num,"data"=>$keys);
            header("Content-type: application/json");
            echo json_encode($data);
        break;

    case "changekey":
        $error = 0;
        $file = "..\\..\\..\\urls.json";
        $oldkey = getParam("oldkey"); $newkey = test_input(getParam("newkey")); 
        if($oldkey == NULL || $newkey == NULL){
            $error = "无效的 KEY 值";
        }else{
            $keyjson = json_decode(file_get_contents($file),true);
            if(!array_key_exists($oldkey,$keyjson)){
                $error = "无效的 KEY 值";
            }else{
                if(array_key_exists($newkey,$keyjson)) $error = "重复的 KEY 值";
                if($error === 0){
                    $key_array = array_keys($keyjson);
                    foreach($key_array as $key => $value){
                        if($value == $oldkey) $key_array[$key] = $newkey;
                    }
                    file_put_contents($file,json_encode(array_combine($key_array,array_slice($keyjson,0))));
                }
            }
        }
        header("Content-type: application/json");
        echo json_encode(array("error" => $error));
        break;

    case "changenum":
        $file = "..\\..\\..\\urls.json";
        $oldkey = getParam("oldkey"); $newnum = test_input(getParam("newnum"));
        if($newnum == NULL){
            $error = "无效的 URL 值";
        }else{
            $keyjson = json_decode(file_get_contents($file),true);
            if(!array_key_exists($oldkey,$keyjson)){
                $error = "无效的 KEY 值";
            }else{
                $keyjson[$oldkey] = $newnum;
                file_put_contents($file,json_encode($keyjson));
                $error = 0;
            }
        }
        header("Content-type: application/json");
        echo json_encode(array("error" => $error));
        break;

    default:
        header("Content-type: application/json");
        echo json_encode(array("error" => "Bad Request"));
}

//Functions
function get_new_key(){
    $error = 0;
    $chars32 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $key = "";
    for($i=0;$i<5;$i++) $key .= $chars32[mt_rand(0,61)];
    $url_base = json_decode(file_get_contents("..\\..\\..\\urls.json"),true);
    if(array_key_exists($key,$url_base)) $error = 1;
    if($error === 0) return $key; else return get_new_key();
}