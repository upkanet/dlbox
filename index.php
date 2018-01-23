<?php 

require_once 'config.php';
require 'vendor/autoload.php';
use Jenssegers\Blade\Blade;

require 'dir.php';
require 'login-check.php';

$blade = new Blade('views', 'cache');

$path = $_GET['dir'] ?? null;

$directory = new Dir($path);

$alert = null;

$freespace = getFreespace();

if(isset($_GET['del'])){
	$alert = $directory->del($_GET['del']);
	$directory->loadFiles();
}
echo $blade->make('homepage', ['directory' => $directory, 'alert' => $alert, 'freespace' => $freespace]);

function getFreespace(){
	$shell = shell_exec('df /dev/vda1');
	$lines = preg_split("/((\r?\n)|(\r\n?))/", $shell);
	$values = preg_split('/\s+/',$lines[1]);
	return "Free disk space : " . fFilesize($values[3]*1000)."o (".$values[4].")";
}

function fFilesize($bytes, $decimals = 1) {
	$sz = 'bkMGTP';
	$factor = floor((strlen($bytes) - 1) / 3);
	return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . " " . @$sz[$factor];
}