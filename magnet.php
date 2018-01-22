<?php
	$resp_a = ['message' => '', 'type' => 'success'];

	if(!isset($_GET['magnet']) || $_GET['magnet']==""){
		$resp_a['message'] = 'Missing Magnet';
		$resp_a['type'] = 'danger';
	}
	else{
		$magnet = urldecode($_GET['magnet']);
		$cmd = 'deluge-console "add ' . $magnet . '"';
		$message = shell_exec($cmd);
		if($message != "Torrent added!\n"){
			$resp_a['type'] = 'danger';
		}
		$resp_a['message'] = $message;
	}
	
	echo json_encode($resp_a);
?>
