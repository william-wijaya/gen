<?php
namespace Plum\Gen;

class CodeSpace
{
    /**
     * The code space directory
     *
     * @var string
     */
    private $dir;

    /**
     * The store filename
     *
     * @var string
     */
    private $storeFile;

    /**
     * Constructor
     *
     * @param string $dir
     *
     * @throws \UnexpectedValueException
     */
    public function __construct($dir)
    {
        $dir = rtrim($dir, "/")."/";
        $d = new \SplFileInfo($dir);
        if (!$d->isDir()) throw new \UnexpectedValueException(
            "{$dir} is not a directory"
        );
        if (!$d->isReadable()) throw new \UnexpectedValueException(
            "{$dir} is not readable"
        );
        if (!$d->isWritable()) throw new \UnexpectedValueException(
            "{$dir} is not writable"
        );

        $this->dir = $dir;
        $this->storeFile = $this->dir.".cs_store";

        touch($this->storeFile);
    }

    /**
     * @param string $class
     * @param CodeWriter $writer
     */
    public function save($class, CodeWriter $writer)
    {
        $filename = $this->dir.$class.".php";

        file_put_contents($filename.".tmp", $writer->bufferedCode());
        file_put_contents(
            $this->storeFile, basename($filename).PHP_EOL, FILE_APPEND);

        rename($filename.".tmp", $filename);
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function load($class)
    {
        if (class_exists($class, false))
            return true;

        $filename = $this->dir.$class.".php";
        if (!is_file($filename))
            return false;

        require $filename;

        return class_exists($class, false);
    }

    /**
     * Clears all codes in the space
     */
    public function clear()
    {
        $store = fopen($this->storeFile, "r");
        while ($line = fgets($store)) {
            unlink($this->dir.trim($line));
        }

        unlink($this->storeFile);
        touch($this->storeFile);
    }
} 
