<?php
namespace Aulia\ImageUpload\Filesystem;

use Aulia\ImageUpload\Filesystem\Filesystem;

class Simple implements Filesystem
{

    /**
     * @see Filesystem
     */
    public function isFile(String $path): bool
    {
        return is_file($path);
    }

    /**
     * @see Filesystem
     */
    public function isDir(String $path): bool
    {
        return is_dir($path)   ;
    }

    /**
     * @see Filesystem
     */
    public function moveUploadedFile(String $source,String $destination): bool
    {
        return copy($source, $destination) && unlink($source);
    }

    /**
     * @see Filesystem
     */
    public function doesFileExist(String $path): bool
    {
        return file_exists($path);
    }

    /**
     * @see Filesystem
     */
    public function delete(String $path): bool
    {
        return unlink($path);
    }

    /**
     * @see Filesystem
     */
    public function getSize(String $path): int
    {
        return filesize($path);
    }
    
    /**
     * @see Filesystem
     */
    public function isUploadedFile(string $path): bool
    {
        return is_uploaded_file($path);
    }

    /**
     * @see Fileystem 
     */
    public function writeToFile(string $path, mixed $data, int $flags = 0): bool
    {
        return file_put_contents($path, $data, $flags);
    }

    /**
     * @see Filesystem
     */
    public function chmod(string $filename, int $permission): bool
    {
        return chmod($filename, $permission);
    }

    /**
     * @see Filesysten
     */
    public function getInputStream()
    {
        return fopen('php://input', 'r');
    }
}
