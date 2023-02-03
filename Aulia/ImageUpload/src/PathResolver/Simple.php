<?php
namespace Aulia\ImageUpload\PathResolver;
use Aulia\ImageUpload\PathResolver\PathResolver;

class Simple implements PathResolver
{
    protected $upload_path;

    /**
     * Constructor
     * @param string $path
     */
    public function __construct( String $path)
    {
       $this->upload_path = $path;
    }

    /**
     * @see PathResolver
     */
    public function getUploadPath( String $name = null): string
    {
        return $this->upload_path . '/' . $name;
    }    
}