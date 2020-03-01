<?php
require("./global.php");
require(__ROOT__."/lib/simple_html_dom.php");

header('Content-Type: text/html; charset=utf-8');
 
class NeteaseMusicAPI
{
    private $ENCRYPT_N = '00e0b509f6259df8642dbc35662901477df22677ec152b5ff68ace615bb7b725152b3ab17a876aea8a5aa76d2e417629ec4ee341f56135fccf695280104e0312ecbda92557c93870114af6c9d05c4f7f0c3685b7a46bee255932575cce10b424d813cfe4875d3e82047b97ddef52741d546b8e289dc6935b3ece0462db0a22b8e7';
    private $ENCRYPT_NONCE = '0CoJUm6Qyw8W8jud';
    private $ENCRYPT_E='010001';
    private $AES_VI='0102030405060708';
    protected $_USERAGENT='Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.157 Safari/537.36';
 
    protected $_REFERER='http://music.163.com/';
    // key
    protected $secretKey='TA3YiYCfY2dDJQgg';
    protected $encSecKey='84ca47bca10bad09a6b04c5c927ef077d9b9f1e37098aa3eac6ea70eb59df0aa28b691b7e75e4f1f9831754919ea784c8f74fbfadf2898b0be17849fd656060162857830e241aba44991601f137624094c114ea8d17bce815b0cd4e5b8e2fbaba978c6d1d14dc3d1faf852bdd28818031ccdaaa13a6018e1024e2aae98844210';
 
    private function prepare($raw)
    {
        $data['params'] =$this->aes_encode(json_encode($raw), $this->ENCRYPT_NONCE);
        $data['params'] = $this->aes_encode($data['params'], $this->secretKey);
        $data['encSecKey'] = $this->encSecKey;
        return $data;
    }
 
    private function aes_encode($secretData, $secret)
    {
        return openssl_encrypt($secretData, 'aes-128-cbc', $secret, false, $this->AES_VI);
    }
 
    /**
     * CURL 模块
     * @param  string $uri      目的地址
     * @param  string $postData POST数组
     * @param  string $cookie   携带Cookie
     * @param  string|array $header   自定义Header
     * @return string
     */
    protected function http_requests($uri, $postData = '', $cookie = '', $header = '') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
 
        if ($postData) { // post提交
            if (is_array($postData)) $postData = http_build_query($postData);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }
 
        if ($cookie) // 伪造cookie
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
 
        if ($header) // 自定义header
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
 
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
 
 
    public function url($song_id, $br = 999000){
        $url = 'http://music.163.com/weapi/song/enhance/player/url?csrf_token=';
        if (!is_array($song_id)) $song_id = [$song_id];
        $data = [
            'ids' => $song_id,
            'br' => $br,
            'csrf_token' => '',
        ];
        return $this->http_requests(
            $url,
            $this->prepare($data),
            'os=pc; osver=Microsoft-Windows-10-Professional-build-10586-64bit; appver=2.0.3.131777; channel=netease; __remember_me=true',
            [
                'Origin: http://music.163.com',
                'X-Real-IP: 183.30.197.115',
                'Accept-Language: q=0.8,zh-CN;q=0.6,zh;q=0.2',
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36',
                'Referer: http://music.163.com/'
            ]
        );
    }
}
 
//$a = new NeteaseMusicAPI();
//$result = $a->url('1324187377', 96000);
//$array = json_decode($result, true);
//var_dump($array);

//--------------------------------------------------------------------------------------------
$html = new simple_html_dom();
$html->load(file_get_contents("https://music.163.com/song?id=1324187377&userid=531092950"));
$obj = $html->find("script[type=application/ld+json]", 0); //寻找第一个 type=application/ld+json 的 script

$json = $obj->innertext;
if($json == "")
	die();
$json = json_decode($json);

$img = $json->images[0];
echo "<img src=$img></img>";

$html->clear(); //释放内存

?>