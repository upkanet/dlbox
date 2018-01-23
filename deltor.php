<?php

$id = $_GET['id'] ?? "";

if($id != ""){
	if(ctype_xdigit($id)){
		$cmd = "deluge-console rm ".$id;
		//$message = shell_exec($cmd);
		echo $cmd;
	}
}
