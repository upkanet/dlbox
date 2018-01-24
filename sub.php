<?php
require 'login-check.php';

$dir = $_GET['dir']
        or exit("No directory specified");

if(substr($dir, -1) != "/"){
	$dir .= "/";
}

if(file_exists($dir)){
	$shell = shell_exec("subbatch ".$dir);
	$lines = preg_split("/((\r?\n)|(\r\n?))/", $shell);
	echo json_encode(["type" => "success", "message" => (count($lines) - 1)." subtitles downloaded"]);
}

