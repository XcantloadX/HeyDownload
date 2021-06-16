var doneBtn, url, video, vidtitle;

$(function(){
	$("#done").click(function(){
		$.get("get.php?url=" + $("#url").val(), function(data){
			console.log("Server returned: ", data);
			showvideo(data);
		});
	});
});

function showvideo(data){
    var json = undefined;
    try{
        json = JSON.parse(data);
    }
	catch{
		console.error("服务器返回异常。");
		return;
    }
	
	//检查错误
	if(json.code != 0)
	{
		alert("错误：" + json.msg);
        return;
	}
	
	var url = json.data.urls[0].url;
	
	vidtitle.innerText = json.data.title;
	video.src = url;
}