<?php
//总解析器
$websites = array(
	"youku.com" => "YouKu", //域名 => 类名
	"haokan.baidu.com" => "Haokan",
	"bilibili.com" => "Bilibili",
	"music.163.com" => "CloudMusic"
);

//使用 URL 解析视频
function parseByUrl($url){
	global $websites;
	$parser = null;
	
	//遍历配对
	foreach($websites as $k=>$v){
		if(strpos($url, $k) > 0) //如果匹配成功
			$parser = loadParser($v);
	}
	
	if($parser != null)
		$parser->createFromUrl($url);
	return $parser;
}

//加载解析器
function loadParser($name){
	chdir(dirname(__FILE__)); //切换目录
	include(__ROOT__."/parser/".strtolower($name).".php"); //加载解析器
	return call_user_func("new".$name); //实例化对象
}