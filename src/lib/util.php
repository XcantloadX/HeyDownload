<?php
//重定向
function redirect(string $url) : void{
	header("Location: $url");
    exit;
}

//URL 参数解析
function getUrlParams(string $url){
    $url = parse_url($url); //解析 url
    $param = $url["query"]; //获取参数
    $param = explode("&", $param);
	
    $params = array();
    for($i = 0; $i < count($param); $i++){
        $tmp = explode("=",$param[$i]);
		$params[$tmp[0]] = $tmp[1]; //插入主数组
    }
	
	return $params;
} 

/*
* 结束执行，并返回错误信息
* @param msg 信息
* @param code HTTP 状态码
*/
function fail(string $msg, int $httpcode){
    header("HTTP/1.1 $httpcode");
    header("Content-Type: application/json");
    die("{\"code\": -1, \"msg\": $msg }");
}

/*
* 结束执行，并返回 json 文本
* @param obj 要输出的 Object
*/
function succeed(array $obj) : void{
    header("Content-Type: application/json");
    echo json_encode($obj);
}

function makeResponse(int $code, string $msg, string $type, array $urlList, array $infoList){
    $data = array(
            "type" => $type,
            "urls" => $urlList,
    );
    $data = array_merge($data, $infoList);
    
    return array(
        "code" => $code,
        "msg" => $msg,
        "data" => $data
    );
}