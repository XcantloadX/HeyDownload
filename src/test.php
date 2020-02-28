<?php
require("global.php");
require(__ROOT__."/parser/parsers.php");
//TODO: API 错误检测

header("Content-Type:text/html;charset=utf-8");

$haokan = parseByUrl("http://haokan.baidu.com/v?vid=11222495673540217469");
$haokan->parse();
var_dump($haokan->getList());