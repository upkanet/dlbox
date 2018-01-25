<?php
require 'login-check.php';

$dir = $_GET['dir']
        or exit("No directory specified");

if(substr($dir, -1) != "/"){
	$dir .= "/";
}

if(file_exists($dir)){
	$shell = shell_exec("subliminal download ".$dir." -l fr");
	$lines = preg_split("/((\r?\n)|(\r\n?))/", $shell);
	array_shift($lines);
	array_pop($lines);

	$message = $lines[0];
	if(count($lines)>2){
		$message .= '<br>'.end($lines);
	}

	echo json_encode(["type" => "info", 
			"message" => $message]);
}