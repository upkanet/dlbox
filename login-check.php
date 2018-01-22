<?php
include_once('config.php');
session_start();

if(!isset($_SESSION['password']) || $_SESSION['password'] != $_CONFIG['password']){
	header('Location: login-form.php');
}
