<?php
class ResponseMaker
{
	private $json;
	
	function __construct()
	{
		$this->json = array(
			"code" => 0,
			"msg" => "No error.",
			"video" => array(
				"url" => "",
				"title" => "",
				"cover" => ""
			)
		);
		$this->json["video"] = array();
		$this->setMsg("No error.");
		$this->setCode(0);
	}
	
	//发送响应
	function send()
	{
		header("Content-Type: application/json; charset=utf-8");
		if($this->json["code"] != 0)
			header("HTTP/1.1 400");
		else
			header("HTTP/1.1 200");
		
		echo json_encode($this->json);
	}
	
	function fail($code, $msg)
	{
		$this->setCode($code);
		$this->setMsg($msg);
		$this->sendAExit();
	}
	
	//发送并退出
	function sendAExit()
	{
		$this->send();
		exit;
	}
	
	//设置提示信息
	function setMsg($msg)
	{
		$this->json["msg"] = $msg;
	}
	
	function setCode($code)
	{
		$this->json["code"] = $code;
	}
	
	function setUrl($url)
	{
		$this->json["video"]["url"] = $url;
	}
	
	function setTitle($title)
	{
		$this->json["video"]["title"] = $title;
	}
}


?>