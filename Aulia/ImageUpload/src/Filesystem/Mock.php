<?php
namespace Aulia\ImageUpload\Filesystem;

use Aulia\ImageUpload\Filesystem\Filesystem;

class Mock implements Filesystem
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
        return copy($source, $destination);
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
}
