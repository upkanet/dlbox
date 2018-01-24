<?php
	include('config.php');
	require 'vendor/autoload.php';
	use Jenssegers\Blade\Blade;
	session_start();
	if(isset($_GET['pw'])){
		if(password_verify($_GET['pw'],$_CONFIG['password'])){
			$_SESSION['password'] = $_CONFIG['password'];
			header('Location: index.php');
		}
		else{
			$_SESSION['password'] = null;
			echo "Wrong password<br>";
		}
	}
	else{
		$_SESSION['password'] = null;
	}

	$blade = new Blade('views','cache');
	echo $blade->make('login');

?>
