<?php
namespace Aulia\ImageUpload\Filesystem;

interface Filesystem
{
    /**
     * Check if it is a file
     * @param string $path Location
     * @return boolean
     */
    public function isFile(String $path): bool;
    
    /**
     * Check if it is a directory
     * @param string $path Location
     * @return boolean
     */
    public function isDir(String $path): bool;

    /**
     * Move uploaded file
     * @param string $source From
     * @param string $destination To
     * @return boolean
     */
    public function moveUploadedFile(String $source, String $destination): bool;

    /**
     * Check if file exists
     * @param string $path Location of the file
     * @return boolean
     */
    public function doesFileExist(String $path): bool;

    /**
     * Delete path
     * @param string $path Location
     * @return boolean
     */
    public function delete(String $path): bool;
    /**
     * Get file size
     * @param string $path Location
     * @return boolean
     */
    public function getSize(String $path): int;
    /**
     * Tells whether the file was uploaded via HTTP POST
     * @param string $path Location
     * @return boolean
     */
    public function isUploadedFile(String $path): bool;
}