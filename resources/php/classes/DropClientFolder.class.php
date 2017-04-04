<?php

class DropClientFolder {	

	private $src = "";
	private $clientName = "";
	
	public function __construct($src, $clientName)
    {
		$this->src = $src;
		$this->clientName = $clientName;
    }
	
	public function dropClient() {
		$this->removeDirectory($this->src);
	}
	
	private function removeDirectory($src) {
		
		if(!is_dir($src)) {
			return false;
		}
		
		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($src, RecursiveDirectoryIterator::SKIP_DOTS),
			RecursiveIteratorIterator::CHILD_FIRST
		);

		foreach ($files as $fileinfo) {
			$todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
			$todo($fileinfo->getRealPath());
		}

		rmdir($src);
	}
}
?>