<?php
// Get file paths
//$webVttFile = $argv[1];
$srtFile = $_GET['srt'];

// Read the srt file content into an array of lines
$fileHandle = fopen($srtFile, 'r');
if ($fileHandle) {
    // Assume that every line has maximum 8192 length
    // If you don't care about line length then you can omit the 8192 param
    $lines = array();
    while (($line = fgets($fileHandle, 8192)) !== false) {
        $lines[] = $line;
    }

    if (!feof($fileHandle)) exit ("Error: unexpected fgets() fail\n");
    else ($fileHandle);
}

// Convert all timestamp lines
// The first timestamp line is 1
$length = count($lines);
$newlines = ["WEBVTT\n\n"];

for ($index = 1; $index < $length; $index++) {
    // A line is a timestamp line if the second line above it is an empty line
	if(trim($lines[$index - 1]) === ''){
		$index++;
	}
    if ($index === 1 || trim($lines[$index - 2]) === '') {
        array_push($newlines, substr(str_replace(',', '.', $lines[$index]),0,-1));
    }
	else{
		array_push($newlines, $lines[$index]);
	}
}

// Show
header("Content-Type:text/vtt;charset=utf-8");
for($i = 0; $i < count($newlines); $i++){
	echo $newlines[$i];
    if(substr($newlines[$i],-1) != "\n"){
        echo "\n";
    }
}
