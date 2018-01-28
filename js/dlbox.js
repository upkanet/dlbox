function deleteFile(dir,path,basename){
	if(confirm("Do you really want to delete "+basename+" ?")){
		console.log("Delete " + path + " from " + dir);
		window.location.href = "?dir="+dir+"&del="+path;
	}
}

function showVideo(shortpath,title,progress){
	$.get("video.php?v="+shortpath,function(data){
		$('#videosection').html(data);
		$('#VideoModalTitle').html(title);
		$('#VideoModal').modal('toggle');
		if(progress > 1 && progress < 95){
			var vid = document.getElementById('PlayingVid');
			vid.onloadedmetadata = function(){
				console.log('Video onloadedmetadata fired');
				vid.currentTime = progress / 100 * vid.duration;
			};
		}
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

	if($('#AddMagnetInp').val() != ""){
		$('#AddMagnet').collapse('show');
	}
});


function sendAlert(type,message){
	$("#AlertArea").html('<div id="AlertBox" class="alert alert-'+type+'">'+message+'</div>');
	$("#AlertBox")
		.animate({ width: "100%" },3000)
		.animate({"opacity":"toggle"},1500);
}

function actionMagnet(actionparam){
	$.getJSON("tor.php?action="+actionparam, function(data){
		sendAlert(data.type, data.message);
	});
}

function addMagnet(){
	var mag = $('#AddMagnetInp').val();
	if(mag != "" && mag != null){
		actionMagnet('add&magnet='+mag);
		$('#AddMagnet').collapse('hide');
	}
}

function loadSub(dir){
	var btntxt = 'Load Subtitles';
	$('#LoadSubBtn').html(btntxt + ' <span class="oi oi-clock"></span>');
	$.getJSON("sub.php?dir="+dir, function(data){
		sendAlert(data.type, data.message);
		$('#LoadSubBtn').html(btntxt);
		location.reload();
	});
}

function deleteMagnet(id,name){
	if(confirm('Do you really want to delete '+name+' torrent')){
		actionMagnet('delete&id='+id);
	}
}

function pauseMagnet(id){
	actionMagnet('pause&id='+id);
}

function resumeMagnet(id){
	actionMagnet('resume&id='+id);
}

function updateTorList(){
	var table = $("#TorList");
	$.getJSON("tor.php?action=list",function(tors){
		var str = '';
		tors.forEach(function(t){
			str += '<tr>';
			str += '<td>';
			var playlink = '<a href="javascript:#TOGGLE#Magnet(\'' + t.ID + '\');"><span class="oi oi-#ICON#"></span></a>';
			if(t.State == "Paused"){
				str += playlink.replace('#ICON#','media-pause').replace('#TOGGLE#','resume');
			}
			if(t.State == "Checking"){
				str += playlink.replace('#ICON#','reload').replace('#TOGGLE#','resume');
			}
			if(t.State == "Downloading"){
				str += playlink.replace('#ICON#','media-play').replace('#TOGGLE#','pause');
			}
			if(t.State == "Seeding"){
				str += playlink.replace('#ICON#','share').replace('#TOGGLE#','pause');
			}
			if(t.State == "Queued"){
				str += playlink.replace('#ICON#','align-right').replace('#TOGGLE#','pause');
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

var videotimeupdate = 0;

function updateVidTime(){
	if(videotimeupdate != Math.floor(Date.now()/30000)){
		videotimeupdate = Math.floor(Date.now()/30000);
		var vid = document.getElementById("PlayingVid");
		var p = Math.round(vid.currentTime/vid.duration*10000)/100;
		var s = vid.getElementsByTagName('source')[0].getAttribute('src');
		$.get('track.php?file='+s+'&progress='+p, function(data){
			
		});
	}
}