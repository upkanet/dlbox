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

	$("#AddMagnetBtn").click(function(){
		var mag = prompt("Add Magnet");
		$.getJSON("magnet.php?magnet="+mag, function(data){
			sendAlert(data.type, data.message);
		});
	});
});


function sendAlert(type,message){
	$("#AlertArea").html('<div id="AlertBox" class="alert alert-'+type+'">'+message+'</div>');
	$("#AlertBox").animate({"opacity":"toggle"},1500);
}
