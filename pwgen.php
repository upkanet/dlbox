<?php

$pw = $_GET['pw'] ?? "";

function ph($pw){
	return password_hash($pw, PASSWORD_DEFAULT);
}

echo ph($pw);
