<?php
include_once('config.php');
session_start();
if(password_verify($argv[1],$_CONFIG['password'])){
	$_SESSION['password'] = $_CONFIG['password'];
}
else{
	exit('Wrong Password');
}
$_GET['action'] = "clear";
include('tor.php');