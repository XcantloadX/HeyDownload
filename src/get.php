<?php
require_once 'vendor/autoload.php';
require_once "lib/util.php";
require_once "parser/base.php";

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
    if(strpos($url, $k) > 0){ //如果匹配成功
        include("./parser/".strtolower($v).".php");
        $ins = new $v($url);
        if($redirect){
            header("Location: ".$ins->getUrl());
            exit;
        }else{
            foreach ($actions as $action){
                if($action != "")
                    $data = array_merge($data, $ins->$action());
            }
        }
        $data["type"] = $ins->getType();
        $response = array("code" => $ins->getCode(), "msg" => $ins->getMsg(), "data" => $data);
        header("Content-Type: application/json");
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
}


