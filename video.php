<center>
<video controls preload="metadata" width="470" id="PlayingVid" ontimeupdate="updateVidTime()">
	<source src="<?= $_GET['v'] ?? "" ?>" type="video/mp4">
	<track label="<?php echo subtitles($_GET['v'])['label']; ?>" kind="subtitles" srclang="en" src="<?php echo subtitles($_GET['v'])['path']; ?>" default>
</video>
</center> 
<?php function subtitles($filename){
    $info = pathinfo($filename);
    $cousinfile = $info['dirname'].'/'.$info['filename'];
    $ext = "";
    $label = "English";
    if(file_exists($cousinfile.".srt")){
    	$ext = ".srt";
    }
    elseif (file_exists($cousinfile.".fr.srt")) {
    	$label = "French";
    	$ext = ".fr.srt";
    }
    elseif (file_exists($cousinfile.".en.srt")) {
    	$ext = ".en.srt";
    }
    else{
    	$ext = ".srt";
    }

    return ["label" => $label,
    "path" => "vtt.php?srt=".$cousinfile . $ext ];
}
?>
