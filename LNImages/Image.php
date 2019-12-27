<?php

class Image {
	public $dirname;
	public $basename;
	public $ext;
	public $filename;
	public $source;

	private $_allowedExt = [
		'webp',
		'png',
		'jpg'
	];


	public function __construct() {
		return $this;
	}


	public function setSourceDir($sourceDir) {
		$this->_sourceDir = realpath($sourceDir);
		return $this;
	}

	public function setTargetDir($targetDir) {
		$this->_targetDir = $targetDir;
		return $this;
	}
	

	private function _parseSize() {
		preg_match('/-w(\d+)/', $this->filename, $r);
		$this->_size->width = (isset($r[1])) ? $r[1] : -1;

		preg_match('/-h(\d+)/', $this->filename, $r);
		$this->_size->height = (isset($r[1])) ? $r[1] : -1;

		return $this;
	}
	
	private function _findSource() {
		$this->_sourceFile = $this->_sourceDir . $this->dirname . DIRECTORY_SEPARATOR . $this->filename;


		$this->_sourceFile = preg_replace('/-w(\d+)/', '', $this->_sourceFile);
		$this->_sourceFile = preg_replace('/-h(\d+)/', '', $this->_sourceFile);

		// Original, source, path, without extension

		foreach($this->_allowedExt as $ext) {
			$file = $this->_sourceFile . '.' . $ext;
			if (is_file($file)) {
				$this->_sourceFile = $file;
				return $this;
			}
		}
		return $this;
	}

	public function createTargetDir() {
		$target = realpath($this->_targetDir) . DIRECTORY_SEPARATOR . $this->dirname;
		if (!is_dir($target)) {
			mkdir($target, 0755, true);
		}

	}

	public function _prepareTarget() {
		$this->createTargetDir();
		$this->targetFile = realpath($target) . DIRECTORY_SEPARATOR . $this->filename . '.' . $this->ext;
		return $this;
	}

	public function _execute() {
		// ffmpeg -i amazing-80s.webp -vf scale=200:400 x.png

		$cmd = '/usr/bin/ffmpeg ';
		$params[] = '-i ' . $this->_sourceFile;
		$params[] = ' -lossless 1 -compression_level 0 -vf scale=' . $this->_size->width . ':' . $this->_size->height;
		$params[] = $this->targetFile;

		shell_exec($cmd . join(' ', $params));


	}


	public function create($image) {

		$this->targetFile = $image;
		$pi = pathinfo($image);

		$this->dirname	= $pi['dirname'];
		$this->basename	= $pi['basename'];
		$this->ext		= $pi['extension'];
		$this->filename	= $pi['filename'];
		unset($pi);

		$this->_size = new StdClass();
		$this->_parseSize()
				->_findSource()
				->_prepareTarget()
				->_execute();
	}
}