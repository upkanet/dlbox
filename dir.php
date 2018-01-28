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
	public $shortnav = [];

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
		$this->getNav();
		$this->isbasedir = ($this->path == realpath($this->basedir));
		
		$this->loadFiles();
	}

	public function loadFiles(){
		//Files list
		$this->files = [];
		$files_array = scandir($this->path);
		//Remove current folder (".")
		array_shift($files_array);
		//Remove parent dir if this is the root directory
		if($this->isbasedir) array_shift($files_array);
		//Remove hidden files
		$files_array = preg_grep('/^([^.])/', $files_array);
		//Create File object for each path entry
		foreach($files_array as $fa){
			$f = new File($fa,$this->path.'/'.$fa,$this->basedir);
			array_push($this->files, $f);
		}
		$this->sortFiles();

	}

	private function sortFiles(){
		$dirs = [];
		$files = [];
		foreach($this->files as $f){
			if($f->isdir){
				array_push($dirs,$f);
			}
			else{
				array_push($files,$f);
			}
		}
		asort($dirs);
		asort($files);
		$this->files = array_merge($dirs, $files);
	}


	private function subdir($path){
		return !(strpos($path, realpath($this->basedir)) === false);
	}

	private function getNav(){
		$arr = explode('/',$this->shortpath);
		$nav = [];
		$snav = [];
		$sp = "";
		foreach($arr as $d){
			$sp .= "/".$d;
			$nav[$d] = substr($sp,1);
			if(strlen($d)>10){
				$snav[substr($d,0,10)."..."] = substr($sp,1);
			}
			else{
				$snav[$d] = substr($sp,1);
			}
		}
		$this->nav = $nav;
		$this->shortnav = $snav;
	}

	public function del($shortpath){
		$path = realpath($shortpath);
		if($this->subdir($path)){
			$info = pathinfo($path);
			$f = new File($info['filename'],$path, $this->basedir);
			if($f->del()){
				$a = ["type" => "success", "message" => $f->basename." deteled"];
			}
			else{
				$a = ["type" => "danger", "message" => "Unable to delete ".$f->basename];
			}
		}
		else{
			$a = ["type" => "danger", "message" => "Deletion error"];
		}
		return $a;
	}

}

class File
{
	public $name;
	public $basename;
	public $shortname;
	public $path;
	private $basedir;
	public $shortpath;
	public $isdir = false;
	public $extension = "";
	public $size = 0;
	public $istvshow = false;
	public $tvshow = [];

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
		if(strlen($this->name)>25){
			$this->shortname = substr($this->name,0,25)."...";
		}
		else{
			$this->shortname = $this->name;
		}
		$this->path = realpath($path);
		$this->basedir = $basedir;
		$this->shortpath = $this->basedir . str_replace(realpath($this->basedir), "", $this->path);
		$this->isdir = is_dir($this->path);
		if(!$this->isdir){
			$info = pathinfo($this->path);
			$this->name = $info['filename'];
			$this->extension = $info['extension'];
			$this->basename = $this->name.".".$this->extension;
			$this->shortname = $this->shortname.".".$this->extension;
			$this->size = $this->filesize();
			$this->type = $this->getType();
			$this->icon = $this->getIcon();
			$this->checkTVShow();
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

	private function checkTVShow(){
		if ($this->type == "video" && preg_match("'^(.+)\.S([0-9]+)E([0-9]+).*$'i",$this->name,$n))
		{
			$this->istvshow = true;
		    $this->tvshow["name"] = preg_replace("'\.'"," ",$n[1]);
		    $this->tvshow["season"] = intval($n[2],10);
		    $this->tvshow["episode"] = intval($n[3],10);
		}
	}

	public function del(){
		if($this->isdir){
			return rmdir_recursive($this->path);
		}
		else{
			return unlink($this->path);
		}
	}

	public function getDir(){
		return dirname($this->shortpath);
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
