<?php
//重定向
function redirect($url){
	header("Location: $url");
}

//URL 参数解析
function getUrlParams($url){
    $url = parse_url($url); //解析 url
    $param = $url["query"]; //获取参数
    $param = explode("&", $param);
	
    $params = array();
    for($i = 0; $i < count($param); $i++){
        $tmp = explode("=",$param[$i]);
		$params[$tmp[0]] =  $tmp[1]; //插入主数组
    }
	
	return $params;
} 