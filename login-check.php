<?php
include_once('config.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['password']) || $_SESSION['password'] != $_CONFIG['password']){
	session_destroy();
	header('Location: login-form.php');
}
