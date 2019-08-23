<?php
//所有解析器的基类
abstract class Processer
{
	abstract public function process($data);
	abstract public function response($responsemaker);
}

?>