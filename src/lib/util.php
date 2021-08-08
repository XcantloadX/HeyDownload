<?php
define("UA_WIN10_EDGE", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.131 Safari/537.36 Edg/92.0.902.67");

/** 重定向到指定 URL
 * @param string $url URL
 */
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

/**
* 结束执行，并返回错误信息
* @param string msg 信息
* @param int code HTTP 状态码
*/
function fail(string $msg, int $httpcode){
    header("HTTP/1.1 $httpcode");
    header("Content-Type: application/json");
    die("{\"code\": -1, \"msg\": $msg }");
}

/**
* 结束执行，并返回 json 文本
* @param array obj 要输出的 Object
*/
function succeed(array $obj){
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

/** $_GET[] 的封装
 * @param string $name URL 参数名称
 * @return mixed|null 值
 */
function _get(string $name){
    if(isset($_GET[$name]))
        return $_GET[$name];
    else
        return null;
}

/** 判断请求 URL 中是否存在某个参数
 * @param string $name URL 参数名称
 * @return bool 是否存在
 */
function _has(string $name) : bool{
    return isset($_GET[$name]);
}

/** 获取请求的 Action
 * @return false|string[] action Array
 */
function _getActions(){
    $str = _get("action");
    if($str == null || $str == "")
        fail("action 为空", 400);
    return explode(",", $str);
}