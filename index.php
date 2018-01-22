<?php 

require_once 'config.php';
require 'vendor/autoload.php';
use Jenssegers\Blade\Blade;

require 'dir.php';
require 'login-check.php';

$blade = new Blade('views', 'cache');

$path = $_GET['dir'] ?? null;

$directory = new Dir($path);

$message = null;

if(isset($_GET['del'])){
	$message = $directory->del($_GET['del']);
	$directory->loadFiles();
}
echo $blade->make('homepage', ['directory' => $directory, 'message' => $message]);
