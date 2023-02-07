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
        if($name === null)
        {
            return $this->upload_path . '/' . $name;
        } else if (file_exists($this->upload_path . '/' . $name))
        {
            return $this->duplicateGuard($name);
        } else
        {
            return $this->upload_path . '/' . $name;
        }
    }

    /**
     * If file already exists, rename it with number by the end of the file.
     * @var string $name
     * @return string $full_path
     */
    protected function duplicateGuard(String $name): String
    {
        $bakuretsu = explode(".", $name);
        $justBeforeExt = array_key_last($bakuretsu) - 1;
        $number = 0;

        while(file_exists($this->upload_path . '/' . $name))
        {
            if(preg_match("/\([0-9]+\)$/", $bakuretsu[$justBeforeExt]))
            {
                $bakuretsu[$justBeforeExt] = trim(preg_replace("/\([0-9]+\)$/", "", $bakuretsu[$justBeforeExt]));
            }
                ++$number;
                $bakuretsu[$justBeforeExt] = $bakuretsu[$justBeforeExt] . " (" . ++$number . ")";
                $name = implode(".", $bakuretsu);
        }
        
        return $this->upload_path . '/' . $name;
    }
}