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
			session_destroy();
			echo "Wrong password<br>";
		}
	}
	else{
		session_destroy();
		$_SESSION['password'] = null;
	}

	$blade = new Blade('views','cache');
	echo $blade->make('login');

?>
