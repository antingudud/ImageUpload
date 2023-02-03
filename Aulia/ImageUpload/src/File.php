<?php
namespace Aulia\ImageUpload;

use InvalidArgumentException;

class File extends \SplFileInfo
{
    /**
     * @var string
     */
    protected $mimeType;
    protected $clientFileName;

    public function __construct(String $filename, $clientFileName = '')
    {
        $this->setMimeType($filename);
        $this->clientFileName = $clientFileName;
        parent::__construct($filename);
    }
    protected function setMimeType(String $filename)
    {
        if(file_exists($filename))
        {
            $this->mimeType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filename);
            return;
        }
    }

    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Check if this file have image mime type.
     * @return boolean
     */
    public function isImage()
    {
        return in_array(
            $this->mimeType,
            ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/png']
        );
    }
}