<?php
require_once 'vendor/autoload.php';
require_once "lib/util.php";
require_once "parser/base.php";

//https://www.php.net/manual/en/reserved.variables.argv.php#113614
//将命令行参数复制到 $_GET 里
if(isCommandline()){
    foreach ($argv as $arg) {
        $e = explode("=", $arg, 2);
        if(count($e)==2)
            $_GET[$e[0]]=$e[1];
        else   
            $_GET[$e[0]]=0;
    }
}

//是否直接重定向到目标地址
$redirect = _has("redirect");
$url = _get("url");
$raw = _has("raw");

//处理 actions
if(!isset($_GET["actions"]))
    $actions = array("getUrls", "getCoverUrl", "getTitle", "getAuthor");
else
    $actions = explode(",", $_GET["actions"]);
if(in_array("default", $actions)){
    unset($actions[array_search("default", $actions)]);
    $actions = array_merge($actions, array("getUrls", "getCoverUrl", "getTitle", "getAuthor"));
}

if($url == ""){
    header("HTTP/1.1 400");
    fail("url 为空。", 400);
}
    
//域名 => 类名
$parsers = array(
	"youku.com" => "YouKu",
	"haokan.baidu.com" => "HaoKan",
	"bilibili.com" => "Bilibili",
    "music.163.com" => "Cloudmusic",
    "y.qq.com" => "QQMusic",
    "music.qq.com" => "QQMusic",
);

$data = array();
//遍历配对
foreach($parsers as $k=>$v){
    if(strpos($url, $k) > 0){
        //匹配成功
        include("./parser/".strtolower($v).".php");
        $ins = new $v($url);
        if($redirect){ //直接重定向到播放地址
            header("Location: ".$ins->getUrl());
            exit;
        }else{ //否则执行所有 action
            foreach ($actions as $action){
                if($action != ""){
                    $retval = $ins->$action(); //action 返回值
                    $actionRetName = lcfirst(str_replace("get", "", $action)); //出现在 json 返回值里的名字
                    $actionRetData = array($actionRetName => $retval);
                    $data = array_merge($data, $actionRetData);
                }
                    
            }
        }
        $data["type"] = $ins->getType();
        $response = array("code" => $ins->getCode(), "msg" => $ins->getMsg(), "data" => $data);
        header("Content-Type: application/json");
        if(isCommandline())
            echo json_encode($response, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); //控制台使用格式化的 json 输出
        else
            echo json_encode($response, JSON_UNESCAPED_UNICODE); //web 使用压缩的 json 输出
    }
}


