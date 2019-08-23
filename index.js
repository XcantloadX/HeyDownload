var doneBtn, id;

window.onload = function(){
	doneBtn = document.getElementById("done");
	id = document.getElementById("id");
	
	doneBtn.onclick = function(){
		ajax({
			url: "yk.php?vid=" + id.value,
			callback: function(data){
				console.log("得到链接：" + data);
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

function showvideo(url){
	var vid = document.createElement("video");
	vid.src = url;
	vid.controls = "controls";
	vid.autoplay = "autoplay";
	document.body.append(vid);
}