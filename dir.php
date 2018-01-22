<?php
require_once 'config.php';
global $_CONFIG;

class Dir
{
	public $name;
	public $path;
	public $shortpath;
	public $files = [];
	private $basedir;
	public $isbasedir = false;
	public $nav = [];

	public function __construct($path){
		global $_CONFIG;
		$this->basedir = $_CONFIG['basedir'];
		//Path handler
		if($path == null){
			$this->path = $this->basedir;
		}
		$this->path = realpath($path);
		if(!$this->subdir($this->path)){
			$this->path = realpath($this->basedir);
		}
		$this->shortpath = $this->basedir.str_replace(realpath($this->basedir),"",$this->path);

		$this->name = basename($this->path);
		$this->nav = $this->getNav();
		$this->isbasedir = ($this->path == realpath($this->basedir));
		
		$this->loadFiles();
	}

	public function loadFiles(){
		//Files list
		$this->files = [];
		$files_array = scandir($this->path);
		array_shift($files_array);
		if($this->isbasedir) array_shift($files_array);
		foreach($files_array as $fa){
			$f = new File($fa,$this->path.'/'.$fa,$this->basedir);
			array_push($this->files, $f);
		}

	}


	private function subdir($path){
		return !(strpos($path, realpath($this->basedir)) === false);
	}

	private function getNav(){
		$arr = explode('/',$this->shortpath);
		$nav = [];
		$sp = "";
		foreach($arr as $d){
			$sp .= "/".$d;
			$nav[$d] = substr($sp,1);
		}
		return $nav;
	}

	public function del($shortpath){
		$path = realpath($shortpath);
		if($this->subdir($path)){
			$info = pathinfo($path);
			$f = new File($info['filename'],$path, $this->basedir);
			if($f->del()){
				$m = ["type" => "secondary", "val" => $f->basename." deteled"];
			}
			else{
				$m = ["type" => "danger", "val" => "Unable to delete ".$f->basename];
			}
		}
		else{
			$m = ["type" => "danger", "val" => "Deletion error"];
		}
		return $m;
	}

}

class File
{
	public $name;
	public $basename;
	public $path;
	private $basedir;
	public $shortpath;
	public $isdir = false;
	public $extension = "";
	public $size = 0;

	public $type = null;
	private $types = [
		"video" => ["mkv", "mp4", "avi"],
		"subtitle" => ["srt"],
		"image" => ["jpg", "jpeg", "png"],
		"info" => ["nfo"],
		"audio" => ["mp3","ogg"],
	];

	public $icon = "file";
	private $icons = [
		"video" => "monitor",
		"subtitle" => "double-quote-serif-right",
		"image" => "image",
		"info" => "info",
		"audio" => "audio-spectrum"
	];


	public function __construct($name,$path,$basedir){
		$this->name = $name;
		$this->basename = $this->name;
		$this->path = realpath($path);
		$this->basedir = $basedir;
		$this->shortpath = $this->basedir . str_replace(realpath($this->basedir), "", $this->path);
		$this->isdir = is_dir($this->path);
		if(!$this->isdir){
			$info = pathinfo($this->path);
			$this->name = $info['filename'];
			$this->extension = $info['extension'];
			$this->basename = $this->name.".".$this->extension;
			$this->size = $this->filesize();
			$this->type = $this->getType();
			$this->icon = $this->getIcon();
		}
	}

	private function filesize($decimals = 1) {
		$bytes = filesize($this->path);
		$sz = 'bkMGTP';
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
	}

	private function getType(){
		$t = null;
		foreach($this->types as $key => $val){
			if(in_array($this->extension, $val)){
				$t = $key;
			}
		}
		return $t;
	}

	private function getIcon(){
		return $this->icons[$this->type] ?? "file";
	}

	public function del(){
		if($this->isdir){
			return rmdir_recursive($this->path);
		}
		else{
			return unlink($this->path);
		}
	}

}


function rmdir_recursive($dir) {
    foreach(scandir($dir) as $file) {
        if ('.' === $file || '..' === $file) continue;
        if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
        else unlink("$dir/$file");
    }
    return rmdir($dir);
}
