<?php
	include('config.php');
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

?>
<form action="" method="get">
<h1>Password</h1>
<input type="password" placeholder="Password" name="pw" id="pw"><input type="submit" value="Log In"/>
</form>
