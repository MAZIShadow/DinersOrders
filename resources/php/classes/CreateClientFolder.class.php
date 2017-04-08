<?php

require_once("HtPasswordGenerate.php");

class CreateClientFolder {

    private $src = '';
    private $dest = '';
    private $clientName = '';
    private $clientPass = '';

    public function __construct($src, $dest, $clientName, $clientPass) {
        $this->src = $src;
        $this->dest = $dest;
        $this->clientName = $clientName;
        $this->clientPass = $clientPass;
    }

    public function createClient() {

        // copy tempFolderData
        if ($this->copyFiles($this->src, $this->dest)) {
            $this->createClientDataFile();
            $this->createHtAccess();
            $tempIndex = $this->dest . "/index.html";
            unlink($tempIndex);
            rename($this->dest . "/tempIndex.html", $tempIndex);

            return true;
        }

        return false;
    }

    private function createHtAccess() {

        $loginClientName = strtolower(preg_replace('/\s+/', '', $this->clientName));
        $file = fopen($this->dest . "/order/.htaccess", "a");
        fwrite($file, 'AuthType Basic' . PHP_EOL);
        fwrite($file, 'AuthName "Panel zamówienia"' . PHP_EOL);
        fwrite($file, 'AuthUserFile \'' . $_SERVER['DOCUMENT_ROOT'] . "/clients/" . $this->clientName . '/.htpasswd\'' . PHP_EOL);
        fwrite($file, 'Require user ' . $loginClientName . PHP_EOL);
        fclose($file);

        $file = fopen($this->dest . "/.htpasswd", "a");
        fwrite($file, $loginClientName . ':' . crypt_apr1_md5($this->clientPass) . PHP_EOL);
        fclose($file);
    }

    private function createClientDataFile() {
        $file = fopen($this->dest . "/config/clientData.php", "a");
        fwrite($file, '<?php' . PHP_EOL);
        fwrite($file, sprintf('$clientName = \'%s\';' . PHP_EOL, $this->clientName));
        fwrite($file, '?>' . PHP_EOL);
        fclose($file);
    }

    private function copyFiles($src, $dest) {

        // If source is not a directory stop processing
        if (!is_dir($src)) {
            return false;
        }

        // If the destination directory does not exist create it
        if (!is_dir($dest)) {
            if (!mkdir($dest)) {
                // If the destination directory could not be created stop processing
                return false;
            }
        }

        // Open the source directory to read in files
        $i = new DirectoryIterator($src);

        foreach ($i as $f) {

            if ($f->isFile()) {
                copy($f->getRealPath(), "$dest/" . $f->getFilename());
            } else if (!$f->isDot() && $f->isDir()) {
                $this->copyFiles($f->getRealPath(), "$dest/$f");
            }
        }

        return true;
    }
}

?>
