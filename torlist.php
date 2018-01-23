<?php

$cmd = "deluge-console info";
$txt = shell_exec($cmd);

$lines = preg_split("/((\r?\n)|(\r\n?))/", $txt);

$torlist = [];

$keys = ["Name","ID","State","Down Speed","Up Speed", "ETA", "Size", "Ratio","Seed time", "Active", "Tracker status", "Progress"];

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

function iB2o($str){
	return str_replace("iB","o",$str);
}

echo json_encode($torlist);
