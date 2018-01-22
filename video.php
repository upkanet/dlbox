<center>
<video controls preload="metadata" width="470">
	<source src="<?= $_GET['v'] ?? "" ?>" type="video/mp4">
	<track label="English" kind="subtitles" srclang="en" src="<?php echo subtitles($_GET['v']); ?>" default>
</video>
</center> 
<?php function subtitles($filename){
	$subfile = repext($filename, "srt");
	return "vtt.php?srt=".$subfile;
}

function repext($filename, $new_extension) {
    $info = pathinfo($filename);
    return $info['dirname'].'/'.$info['filename'] . '.' . $new_extension;
}
?>
