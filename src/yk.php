<?php
define("YK_APPKEY", "24679788");
define("YK_URL", "http://acs.youku.com/h5/mtop.youku.play.ups.appinfo.get/1.1/?");
define("YK_API", "mtop.youku.play.ups.appinfo.get");

class YouKu
{
	private $token;
	private $time;
	private $cookie;
	private $json;
	
	public function __construct()
	{
		$this->cookie = $this->getCookie();
		$this->token = $this->getToken($this->cookie);
		$this->time = time();
	}
	
	public function process($data)
	{
		if(isset($data["vid"]))
			$vid = $data["vid"];
		else
			return;
		
		$this->data = <<<data
{
	"steal_params": "{\"ccode\":\"0502\",\"client_ip\":\"192.168.1.1\",\"utid\":\"NBevFStAZEQCAW40jnu9IcNS\",\"client_ts\":1566290208,\"version\":\"1.8.1\",\"ckey\":\"119#MlKT3NBFM8PGzMMzlyfMRuVLT7EBEbACc6MtYBAsqUnTFatOwvVDvYyAjcplNL8GLeASRBsU3AALuwHNk9SKOrA8RJBONt8L9ei25SSUdGIy/Upp4SMn6rA2RW1zNNFGfeAzR/QYdUeIx4LL7G12qCnxSCqOfoDjsvmw6EOMAOl7Y/h6SYVHIxImmtyIKrTJDojBBgjZTamxD7tViyQxxP+C3W/fByo7iM3PGDP3dzMNrb0Y96bE7k8oJV6e6IaFwcLCuRUspdmc4zcGhpzU4m/8TqqD0cuYnEwbg+pQHpkBd9ALU3j6uFCi9h6jIaRrpTSV7kwAur6WcTODqT1B4d6/MJ9eFwkMZrVn5MabjVXDbKcnmaGmL9aj/4k1yfWkCY0YNhREFvU7N/slngR/mgjDBGPBvvm5CR4PHRrTE4c7DCfnW/xEW31J19xRLyc2P48mIQM2LQxfw2cBJhCDrxZXJBEWyA3XplF7/8a9D5z0BU0THL6GE4ec/ru6n9yNWaSMq5mY/uJNNf9wh3GymAu4hJTGV35dOFSIhSrYsMa3r/Icy4BmbcxCzxIw9f4xqeQxFBo8d8501Zl2vKkrOO2WMrom3RkH1OBfOLUwjPSJqOZ1Y7HFSE0RkD+FHtNhZdE1bTjG3FW56JBXao90g1tWjedX+Q14g9QTbhVSrzkXBbMUIC==\"}",
	"biz_params": "{\"vid\":\"$vid\",\"play_ability\":5376,\"master_m3u8\":1,\"media_type\":\"standard,subtitle\",\"app_ver\":\"1.8.1\"}",
	"ad_params": "{\"vs\":\"1.0\",\"pver\":\"1.8.1\",\"sver\":\"2.0\",\"site\":1,\"aw\":\"w\",\"fu\":0,\"d\":\"0\",\"bt\":\"pc\",\"os\":\"win\",\"osv\":\"7\",\"dq\":\"auto\",\"atm\":\"\",\"partnerid\":\"null\",\"wintype\":\"interior\",\"isvert\":0,\"vip\":0,\"emb\":\"AjEwNzk3MDM3NzMCdi55b3VrdS5jb20CL3Zfc2hvdy9pZF9YTkRNeE9EZ3hOVEE1TWc9PS5odG1s\",\"p\":1,\"rst\":\"mp4\",\"needbf\":2}"
}
data;
		
		//拼接参数
		$params = array(
			"appKey" => YK_APPKEY,
			"t" => $this->time,
			"sign" => md5($this->token."&".$this->time."&".YK_APPKEY."&".$this->data),
			"api" => YK_API,
			"data" => $this->data
		);
		
		$url = YK_URL.http_build_query($params);
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_COOKIE, $this->cookie);
		curl_setopt($ch, CURLOPT_REFERER, "https://v.youku.com/v_show/id_XNDMxODgxNTA5Mg==.html");  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //返回内容储存到变量中 
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36"); //设置 UA
		curl_setopt($ch, CURLOPT_HEADER, false);
		
		$data = curl_exec($ch);
		$json = json_decode($data);
		$this->json = $json;
		
		//返回地址
		return $json->data->data->stream[0]->segs[0]->cdn_url;
	}
	
	//拼接响应 json
	public function response($rm)
	{
		if(!isset($this->json) || !is_object($this->json))
			$rm->fail(-101, "Could not get response json. Maybe wrong params?");
		
		$msg = $this->json->ret[0];
		
		if(strpos("FAIL", $msg))
		{
			$rm->setMsg($msg.". Try request later.");
			$rm->setCode(-100);
			$rm->sendAExit();
		}
		else
			$rm->setCode(0);
		
		$rm->setUrl($this->json->data->data->stream[0]->segs[0]->cdn_url);
		$rm->setTitle($this->json->data->data->video->title);
		$rm->sendAExit();
	}
	
	//获取 Cookie
	private function getCookie()
	{
		//发送请求
		$ch = curl_init("http://acs.youku.com/h5/mtop.youku.play.ups.appinfo.get/1.1/?appKey=24679788&api=mtop.youku.play.ups.appinfo.get");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //返回内容储存到变量中 
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36"); //设置 UA
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		
		$data = curl_exec($ch);
		
		//分割响应头
		$headers = explode(PHP_EOL, $data);
		$cookie = "";
		
		//循环查找 cookie
		for($i = 0; $i < count($headers); $i++)
		{
			if(strpos($headers[$i], "Cookie"))
			{
				//提取 cookie
				$str = str_replace("Set-Cookie:", "", $headers[$i]);
				$str = substr($str, 0, strpos($str, ";"));
				$cookie .= $str . ";";
			}
			
		}
		return $cookie;
	}
	
	//获取 token
	private function getToken($cookie)
	{
		$pos = strpos($cookie, "_m_h5_tk="); //搜索 _m_h5_tk
		return substr($cookie, strlen("_m_h5_tk=") + 1, 32); //截取 token
	}
}
?>