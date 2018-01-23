function deleteFile(dir,path,basename){
	if(confirm("Do you really want to delete "+basename+" ?")){
		console.log("Delete " + path + " from " + dir);
		window.location.href = "?dir="+dir+"&del="+path;
	}
}

function showVideo(shortpath){
	console.log(shortpath);
	$.get("video.php?v="+shortpath,function(data){
		$('#videosection').html(data);
		$('#VideoModal').modal('toggle');
	});
}

$(function(){
	$("#VideoModal").on('hidden.bs.modal', function () {
		$('#videosection').html('');
	});

	$("#AddMagnetBtn").click(addMagnet);

	(function(){
		updateTorList();
		setTimeout(arguments.callee, 3000);
	})();
});


function sendAlert(type,message){
	$("#AlertArea").html('<div id="AlertBox" class="alert alert-'+type+'">'+message+'</div>');
	$("#AlertBox").animate({"opacity":"toggle"},1500);
}

function addMagnet(){
	var mag = prompt("Add Magnet");
	if(mag != "" && mag != null){
		$.getJSON("tor.php?action=add&magnet="+mag, function(data){
			sendAlert(data.type, data.message);
		});
	}
}

function deleteMagnet(id,name){
	if(confirm('Do you really want to delete '+name+' torrent')){
		$.getJSON("tor.php?action=delete&id="+id, function(data){
			sendAlert(data.type, data.message);
		});
	}
}

function updateTorList(){
	var table = $("#TorList");
	$.getJSON("tor.php?action=list",function(tors){
		var str = '';
		tors.forEach(function(t){
			str += '<tr>';
			str += '<td>';
			var playlink = '<a href="toggletor.php?id=' + t.ID + '&toggle=#TOGGLE#"><span class="oi oi-media-#ICON#"></span></a>';
			if(t.State == "Paused"){
				str += playlink.replace('#ICON#','pause').replace('#TOGGLE#','play');
			}
			else{
				str += playlink.replace('#ICON#','play').replace('#TOGGLE#','pause');
			}
			str += '</td>';
			str += '<td>'+t.Name+'</td>';
			str += '<td width="100">';
			if(typeof t["Down Speed"] != 'undefined'){
				str += t["Down Speed"];
			}
			str += '</td>';
			str += '<td width="150"><div class="progress"><div class="progress-bar" role="progressbar" style="width: '+t.Progress+'%">'+t.Progress+'%</div></div></td>';
			str += '<td width="100">'+t.tSize+'</td>';
			str += '<td><a href="javascript:deleteMagnet(\''+t.ID+'\',\''+t.Name+'\');"><span class="oi oi-delete"></span></a></td>';
			str += '</tr>';
		});
		table.html("");
		table.append(str);
		$('#DownloadsBtn').html('Downloads ('+tors.length+')');
	});
}
