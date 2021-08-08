$(function(){
	//解析按钮
	$("#done").click(function(){
		run($("#url").val());
	});

	$("#test").click(function(){
		window.open(window.location.toString() + "\\get.php?url=" + $("#url").val());
	});

	$(".example-url").click(function(){
		$("#url").val($(this).attr("data-url"));
		run($(this).attr("data-url"));
	});
});

function load(data){
	if(data.code !== 0)
	{
		alert("服务器返回错误：" + data.msg);
        return;
	}

	try{
		let url = data.data.urls[0].url;
		$("#type").text(data.data.type);
		$("#author").text(data.data.author);
		$("#title").text(data.data.title);

		if(data.data.type === "audio"){
			loadAPlayer(url, data.data.title, data.data.author, data.data.cover, data.data.lyric);
			$("#player-container").removeClass("s12").addClass("s6");
		}
		else if(data.data.type === "video"){
			loadDPlayer(url, data.cover);
			$("#player-container").removeClass("s6").addClass("s12");
		}
	}catch (e) {
		alert("错误：" + e.toString());
	}finally {
		$("#done").removeClass("disabled");
	}

}

/**
 * 加载音频
 * @param url
 * @param title
 * @param author
 * @param coverUrl
 * @param lrc
 */
function loadAPlayer(url, title, author, coverUrl, lrc){
	window.ap = new APlayer({
		container: $("#player")[0],
		lrcType: 1,
		audio: [{
			name: title,
			artist: author,
			url: url,
			cover: coverUrl,
			lrc: lrc
		}]
	});

	ap.play();
}

function loadDPlayer(url, coverUrl){
	window.dp = new DPlayer({
		container: $("#player")[0],
		screenshot: true,
		video: {
			url: url,
			pic: coverUrl,
			thumbnails: coverUrl,
		}
	});

	dp.play();
}

function run(url){
	$("#done").addClass("disabled");
	$.get("get.php?url=" + url  + "&actions=default,getLyric", function(data){
		console.log("Server returned: ", data);
		if(window.ap != undefined)
			ap.pause();
		if(window.dp != undefined)
			dp.pause();
		load(data);
		$("#done").removeClass("disabled");
	});
}