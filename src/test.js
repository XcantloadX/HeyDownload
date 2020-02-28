$(function(){
	//解析 按钮
	$("#vid-done").click(function(){
		gotoVideo($("#vid-url").val());
	});
});



//获取对应网站的示例视频
function setExampleVideo(website){
	var url = ({
		haokan: "https://haokan.baidu.com/v?vid=11222495673540217469",
		cloudmusic: "https://music.163.com/#/song?id=454129387&userid=531092950",
		
	})[website];
	
	gotoVideo(url);
}

//设置视频
function setVideo(url){
	player = $("#player")[0];
	player.src = url;
	player.play();
}

function gotoVideo(websiteUrl){
	setVideo("get/goto.php?url=" + encodeURIComponent(websiteUrl));
}
