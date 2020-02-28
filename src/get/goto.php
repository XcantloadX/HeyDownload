<?php
//直接重定向到视频地址
require("../global.php");
require(__ROOT__."/parser/parsers.php");

$url = $_GET["url"];
$url = urldecode($url);

$parser = parseByUrl($url);
$parser->parse();
redirect($parser->getUrl());
//echo $parser->getUrl();