<?php
require_once 'vendor/autoload.php';
require_once "lib/util.php";

//是否直接重定向到目标地址
$redirect = isset($_GET["redirect"]) ? true : false;
$url = isset($_GET["url"]) ? $_GET["url"] : "";
$origin = isset($_GET["origin"]) ? true : false;

if($url == ""){
    header("HTTP/1.1 400");
    fail("url 为空。");
}
    
//域名 => php 文件名
$parsers = array(
	"youku.com" => "youku", 
	"haokan.baidu.com" => "haokan",
	"bilibili.com" => "bilibili",
    "music.163.com" => "cloudmusic",
    "y.qq.com" => "qqmusic",
    "music.qq.com" => "qqmusic",
);

//遍历配对
foreach($parsers as $k=>$v){
    if(strpos($url, $k) > 0){ //如果匹配成功
        include("./parser/".$v.".php");
        init(); //这个是 parser 下 php 文件里应该定义的函数
    }
}
