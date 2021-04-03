<?php
include('LNImages/SourceNotFoundException.php');


class Image {
	public $dirname;
	public $basename;
	public $ext;
	public $filename;
	public $source;
	public $fullSourceDir;
	public $imageArray;

	private $_allowedExt = [
		'webp',
		'png',
		'jpg',
		'jp2',
		'ico'
	];


	public function __construct() {
		return $this;
	}


	public function setSourceDir($sourceDir) {
		$this->_sourceDir = realpath($sourceDir);
		return $this;
	}

	public function getSourceDir() {
		return $this->_sourceDir;
	}

	public function setImageArray($imageArray) {
		$this->imageArray = $imageArray;
	}

	public function setTargetDir($targetDir) {
		$this->_targetDir = $targetDir;
		return $this;
	}

	private function _parseSize($imageArray) {
		
		$originalImageWidth = $imageArray[0];
		$originalImageHeight = $imageArray[1];
		$originalAspectRatio = $originalImageWidth/$originalImageHeight;

		preg_match('/-w(\d+)/', $this->filename, $r);
		$this->_size->width = (isset($r[1])) ? $r[1] : -1;

		preg_match('/-h(\d+)/', $this->filename, $r);
		$this->_size->height = (isset($r[1])) ? $r[1] : -1;
		


		//Check whether the inputted Width and Height correspond to the Original Image
		//Makes the NEW IMAGE correspond to the aspectRatio of the Original Image
		//If the width is bigger than the height on the original image, then the width should be also bigger on the NEW IMAGE

		if($originalAspectRatio != $this->_size->width/$this->_size->height) {
			if($originalImageWidth > $originalImageHeight) {
				$this->_size->height = -1;
			}
			else if($originalImageHeight > $originalImageWidth) {
				$this->_size->width = -1;
			}
			else {
				if($this->_size->width > $this->_size->height) { 
					$this->_size->height = $this->_size->width;
				}
				else {
					$this->_size->width = $this->_size->height;
				}
			}
		}

		preg_match('/-l([0,1])/', $this->filename, $r);
		$this->lossy = (isset($r[1])) ? $r[1] : 1;


		

		return $this;
	}

	
	
	private function _findSource() {
		$this->_sourceFile = $this->_sourceDir . $this->dirname . DIRECTORY_SEPARATOR . $this->filename;

		$this->_sourceFile = preg_replace('/-w(\d+)/', '', $this->_sourceFile);
		$this->_sourceFile = preg_replace('/-h(\d+)/', '', $this->_sourceFile);
		$this->_sourceFile = preg_replace('/-l(\d+)/', '', $this->_sourceFile);
		$this->_sourceFile = str_replace('.img', '', $this->_sourceFile);

		// Original, source, path, without extension


		foreach($this->_allowedExt as $ext) {
			$file = $this->_sourceFile . '.' . $ext;
			if (is_file($file)) {
				$this->_sourceFile = $file;

				//Setting Original image size in a property
				$this->setImageArray(getimagesize($file));
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
		$this->_targetDir .= DIRECTORY_SEPARATOR . $this->dirname;
	}

	public function _prepareTarget() {
		$this->createTargetDir();
		$this->targetFile = realpath($this->_targetDir) . DIRECTORY_SEPARATOR . $this->filename . '.' . $this->ext;
		return $this;
	}

	public function _execute() {
		// ffmpeg -i amazing-80s.webp -vf scale=200:400 x.png

		$cmd = '/usr/bin/ffmpeg ';
		$params[] = '-i ' . $this->_sourceFile;
		if ($this->lossy == 1){
			$params[] = '-lossless 0';
		}
		else {
			$params[] = '-lossless 1';
		}
		$params[] = '-compression_level 0';
		$params[] = '-vf scale=' . $this->_size->width . ':' . $this->_size->height;
		$params[] = $this->targetFile;

		shell_exec($cmd . join(' ', $params));
	}


	public function create($image) {
		
		$this->targetFile = $image;
		$pi = pathinfo($image);

		$this->dirname	= $pi['dirname'];
		$this->basename	= $pi['basename'];
		$this->ext	= $pi['extension'];
		$this->filename	= $pi['filename'];
		unset($pi);

		$this->_size = new StdClass();
		$this->_findSource()
				->_parseSize($this->imageArray);

		$fullSourceDir = $this->getSourceDir() . $this->dirname;

		if(is_dir($fullSourceDir)) {
			$this->_prepareTarget()
				->_execute();
		}
		else {
			throw new SourceNotFoundException("Source not found!\n");
		}
		
	}
}
