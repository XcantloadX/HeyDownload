<?php
require "lib/util.php";

//是否直接重定向到目标地址
$redirect = isset($_GET["redirect"]) ? true : false;
$url = isset($_GET["url"]) ? $_GET["url"] : "";
if($url == ""){
    header("HTTP/1.1 400");
    die("url 为空。");
}
    

$websites = array(
	"youku.com" => "youku", //域名 => php 文件名
	"haokan.baidu.com" => "haokan",
	"bilibili.com" => "bilibili",
	"music.163.com" => "cloudmusic"
);

//遍历配对
foreach($websites as $k=>$v){
    if(strpos($url, $k) > 0){ //如果匹配成功
        include("./parser/".$v.".php");
        init($url, $redirect); //这个是 parser 下 php 文件里应该定义的函数
    }
}
