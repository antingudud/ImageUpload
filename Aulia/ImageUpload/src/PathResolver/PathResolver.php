<?php
namespace Aulia\ImageUpload\PathResolver;

interface PathResolver
{
    /**
     * Final destination
     * @param string $name
     * @return string
     */
    public function getUploadPath( String $name = null): String;
}