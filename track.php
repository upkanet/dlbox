<?php
require 'dir.php';
require_once 'config.php';

$path = $_GET["file"] ?? null
	or exit("No file");
$progress = $_GET['progress'] ?? 0;
$info = pathinfo($path);
$file = new File($info['basename'],$path,$_CONFIG['basedir']);

//var_dump($file);
$dir = $file->getDir();
$fname = $file->basename;

//echo "Add \"".$fname."\" progress of ".$progress."% in \"".$dir."\" folder";
$trackfilepath = $dir."/.track";
$trackarr = json_decode(file_get_contents($trackfilepath), true);
//print_r($trackarr);
$trackarr[$fname] = $progress;
file_put_contents($trackfilepath, json_encode($trackarr)."\n");