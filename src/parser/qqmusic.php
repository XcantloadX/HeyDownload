<?php
require_once 'vendor/autoload.php';
//TODO 支持 QQ 音乐另一种 URL https://y.qq.com/n/yqq/song/004Of2MN0iIjD2.html
//TODO 支持封面 URL，歌词 √
//TODO 替换掉 curl 库 √

global $json;

//入口函数
function init(){
  $songid = substr(_get("url"), -14);
  $res = run($songid);
  
  if(_has("redirect")){
    redirect($res["data"]["urls"][0]["url"]);
  }
  else{
    succeed($res);
  }
}

function run(string $songid) : array{
  //post 请求参数
  $json = <<<DATA
{
    "comm": {
        "cv": 4747474,
        "ct": 24,
        "format": "json",
        "inCharset": "utf-8",
        "outCharset": "utf-8",
        "notice": 0,
        "platform": "yqq.json",
        "needNewCode": 1,
        "g_tk_new_20200303": 53883209,
        "g_tk": 53883209
    },
    "req_1": {
        "module": "vkey.GetVkeyServer",
        "method": "CgiGetVkey",
        "param": {
            "guid": "5932328698",
            "songmid": [
                "$songid"
            ],
            "songtype": [
                0
            ],
            "loginflag": 0,
            "platform": "20"
        }
    },
    "req_2": {
        "method": "get_song_detail_yqq",
        "module": "music.pf_song_detail_svr",
        "param": {
            "song_mid": "$songid"
        }
    }
}
DATA;

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "http://u.y.qq.com/cgi-bin/musics.fcg?sign=".makeSign($json), //使用 http，https 有几率造成证书错误之类的
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $json,
    CURLOPT_HTTPHEADER => array(
      'sec-ch-ua: " Not;A Brand";v="99", "Google Chrome";v="91", "Chromium";v="91"',
      'sec-ch-ua-mobile: ?0',
      'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.101 Safari/537.36',
      'Content-Type: application/x-www-form-urlencoded',
      'Referer: https://y.qq.com/'
    ),
  ));
  
  $response = curl_exec($curl);
  
  curl_close($curl);
  $ret = json_decode($response);

  //echo $response;

  //错误检查
  $code = $ret->req_1->code;
  if($response == "Not Found" || $code != 0){
    fail("Request qqmusic server failed. code=".$code, 500);
  }
  
  //获取所有歌手
  $singers = $ret->req_2->data->track_info->singer;
  $singerStr = "";
  foreach ($singers as $v) {
    if($singerStr != ""){
      $singerStr = $singerStr." / ";
    }
    $singerStr = $singerStr.$v->name;
  }

  //获取封面
  //QQ 音乐中单曲封面 = 专辑封面！
    $albumMid = $ret->req_2->data->track_info->album->mid;
  if($albumMid == "" || $albumMid == 0){
      $cover = "";
  }else{
      $cover = "https://y.qq.com/music/photo_new/T002R300x300M000$albumMid.jpg?max_age=2592000";
  }


  //拼接响应
  $res = array(
    "code" => 0,
    "data" => array(
      "type" => "audio",
      "urls" => array(
        0 => array("quality"=>"unknown", "url" => $ret->req_1->data->sip[0].$ret->req_1->data->midurlinfo[0]->purl),
        1 => array("quality"=>"unknown", "url" => $ret->req_1->data->sip[1].$ret->req_1->data->midurlinfo[0]->purl),
      ),
      "title" => $ret->req_2->data->track_info->title,
      "author" => $singerStr,
        "cover" => $cover
    )
  );
  
  return $res;
}

/** 获取歌词
 * @param string $songmid 歌曲 mid
 * @return false|string 歌词
 */
function getLyric(string $songmid, array $data = null){
    $ret = json_decode(Requests::get("https://c.y.qq.com/lyric/fcgi-bin/fcg_query_lyric_new.fcg?_=1626762812506&cv=4747474&ct=24&format=json&inCharset=utf-8&outCharset=utf-8&notice=0&platform=yqq.json&needNewCode=1&songmid=$songmid",
        array("Referer" => "https://y.qq.com/"))->body);
    if($ret == null || $ret->retcode != 0 || $ret->code != 0)
        return "";

    if($data != null)
        $data["lyric"] = $ret->lyric;
    return base64_decode($ret->lyric);
}

/** 生成 sign
 * @param string $param 要提交的参数
 * @return string 生成的 sign
 * @throws Exception 由 bin2hex() 抛出
 */
function makeSign(string $param) : string{
    //https://blog.csdn.net/qq_23594799/article/details/111477320
    //sign = "zza" + 随机十位字符 + md5($param);
    return "zza".bin2hex(random_bytes(5)).md5("CJBPACrRuNy7".$param);
}
