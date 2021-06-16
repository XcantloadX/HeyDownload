$(function(){
	$("#done").click(function(){
		$.get("get.php?url=" + $("#url").val(), function(data){
			console.log("Server returned: ", data);
			showvideo(data);
		});
	});
});

function showvideo(data){
	//检查错误
	if(data.code != 0)
	{
		alert("错误：" + data.msg);
        return;
	}
	
	let url = data.data.urls[0].url;
	$("#type").text(data.data.type);
	$("#author").text(data.data.author);
	$("#title").text(data.data.title);
	$("#vid").attr("src", url);
}