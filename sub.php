<?php
require 'login-check.php';

$dir = $_GET['dir']
        or exit("No directory specified");

if(file_exists($dir)){
	$shell = shell_exec("subbatch ".$dir);
	echo $shell;
}

