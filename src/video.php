<?php
include_once("responsemaker.php");
include_once("yk.php");

$res = new ResponseMaker();

//检查参数
checkParams();

//选择解析器
$data = array();
if(isset($_GET["url"]))
{
	$processer = getProcesser($_GET["url"]);
	$data["url"] = $_GET["url"];
}
else
{
	$processer = new YouKu();
	$data["vid"] = $_GET["vid"];
}

$processer->process($data);
$processer->response($res);

$res->send();


//检查参数
function checkParams()
{
	global $res;
	if(!isset($_GET["vid"]) && !isset($_GET["url"]))
	{
		$res->setCode(-1);
		$res->setMsg("Missing param: \"vid\" or \"url\".");
		$res->sendAExit();
	}
}

function getProcesser($url)
{
	if(strpos("youku", $url))
		return new YouKu();
	else
	{
		$res->setCode(-2);
		$res->setMsg("Website not supported.");
		$res->sendAExit();
	}
}
?>