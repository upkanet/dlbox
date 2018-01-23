<?php
require 'login-check.php';

$action = $_GET['action'] 
	or exit("No action specified");

$cmd = 'deluge-console ';

switch ($action) {
	case 'list':
		fList();
		$cmd .= 'info';
		break;
	
	case 'add':
		checkMagnet($_GET['magnet']);
		fAdd($_GET['magnet']);
		break;

	case 'delete':
		checkID($_GET['id']);
		fDelete($_GET['id']);
		break;


	case 'pause':
		checkID($_GET['id']);
		fPause($_GET['id']);
		break;

	case 'resume':
		checkID($_GET['id']);
		fResume($_GET['id']);
		break;

	default:
		exit("Unknown action");
		break;
}

//Check param integrity
function checkID($id){
	if(!isset($id) || !ctype_xdigit($id)){
		alertMessage("danger","ID Error",true);
		exit();
	}
}

function checkMagnet($magnet){
	if(!isset($magnet) || $magnet == ""){
		alertMessage("danger","Magnet Error",true);
		exit();
	}
}

//Action scripts

//List
function fList(){
	$shell = deluge('info');

	$keys = ["Name","ID","State","Down Speed","Up Speed", "ETA", "Size", "Ratio","Seed time", "Active", "Tracker status", "Progress"];
	
	$lines = preg_split("/((\r?\n)|(\r\n?))/", $shell);
	$torlist = [];
	for($l=0; $l<count($lines);$l++){
		$torblock = "";
		while(trim($lines[$l]) != ""){
			$torblock .= $lines[$l] ." ";
			foreach($keys as $k){
				$torblock = str_replace($k.":","/[".$k."]", $torblock);
			}
			$l++;
		}
		if($torblock != ""){
			$params = explode("/[",$torblock);
			$tor = [];
			foreach($params as $p){
				$delim = strpos($p,"]");
				$pname = substr($p,0,$delim);
				$pval = substr($p,$delim+2,-1);
				$tor[$pname] = $pval;
			}
			cleanTor($tor);
			array_push($torlist,$tor);
		}
	}

	echo json_encode($torlist);
}

//Add
function fAdd($magnet){
	$magnet = urldecode($magnet);
	$shell = deluge('"add '.$magnet.'"');

	$type = "danger";
	if($shell == "Torrent added!\n"){
		$type = "success";
	}

	alertMessage($type, $shell, true);
}

//Delete
function fDelete($id){
	$shell = deluge('rm '.$id);
	alertMessage("success","Torrent deleted",true);
}

//Pause
function fPause($id){
	$shell = deluge('pause '.$id);
	alertMessage("success","Torrent paused",true);
}

//Resume
function fResume($id){
	$shell = deluge('resume '.$id);
	alertMessage("success","Torrent resumed",true);
}

//Helpers
function alertMessage($type,$message,$json=false){
	$a = ["type" => $type,"message" => $message];
	if($json){
		echo json_encode($a);
	}
	return $a;
}

function cleanTor(&$tor){
	unset($tor[""]);
	$s = explode("/",$tor["Size"]);
	$tor["dSize"] = iB2o($s[0]);
	$tor["tSize"] = iB2o($s[1]);
	unset($tor["Size"]);
	$tor["Progress"] = floatval(explode("%",$tor["Progress"])[0]);
	if(isset($tor["Down Speed"])){
		$tor["Down Speed"] = iB2o($tor["Down Speed"]);
		$tor["Up Speed"] = iB2o($tor["Up Speed"]);
	}
}

function deluge($cmd2){
	global $cmd;
	$cmd .= $cmd2;
	return shell_exec($cmd);	
}

function iB2o($str){
	return str_replace("iB","o",$str);
}
