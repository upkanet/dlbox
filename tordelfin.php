<?php

$servroot = 'http://'.$_SERVER['SERVER_NAME'];

$json = file_get_contents($servroot.'/torlist.php');
$tors = json_decode($json);

$alert = [];

foreach($tors as $t){
	if($t->Progress == 100){
		//Delete
		array_push($alert,["action" => "del","id" => $t->ID]);
	}
}

echo json_encode($alert);
