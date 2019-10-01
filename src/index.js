var doneBtn, id, video, vidtitle;

window.onload = function(){
	doneBtn = document.getElementById("done");
	id = document.getElementById("id");
	video = document.getElementById("vid");
	vidtitle = document.getElementById("title");
	
	doneBtn.onclick = function(){
		ajax({
			url: "video.php?vid=" + id.value,
			callback: function(data){
				console.log("服务器返回 json：" + data);
				showvideo(data);
			}
		});
	}
}

function ajax(params){
	var http = new XMLHttpRequest();
	var data;
	var method = params.method ? params.method : "GET";
	var sync = (params.sync == undefined) ? true : false;
	
	http.open(method, params.url, sync);
	http.onreadystatechange = function(){
		if (http.readyState == 4){
			params.callback(http.responseText);
		}
	}
	
	http.send();
}

function showvideo(data){
	var json = JSON.parse(data);
	
	//检查错误
	if(json.code != 0)
	{
		alert("错误：" + json.msg);
	}
	
	var url = json.video.url;
	
	vidtitle.innerText = json.video.title;
	video.src = url;
}