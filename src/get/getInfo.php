<?php
//获取视频信息
require("../global.php");
require(__ROOT__."/parser/parsers.php");

$url = $_GET["url"];
$url = urldecode($url);

$parser = parseByUrl($url);
$parser->parse();
header("Content-Type:application/json; charset=utf-8");
echo json_encode($parser->getInfo());