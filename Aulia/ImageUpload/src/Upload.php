<?php
/**
 * @author Aulia Ridho (antingudud) Raival Aziz <auliaridho51@gmail.com>
 */
namespace Aulia\ImageUpload;

use Aulia\ImageUpload\Filesystem\Filesystem;
use Aulia\ImageUpload\File;
use Aulia\ImageUpload\PathResolver\PathResolver;
use InvalidArgumentException;

class Upload
{
    /**
     * $_SERVER
     * @var Array
     */
    protected $server;

    /**
     * Array of uploaded files
     * @var Array
     */
    protected $files;

    /**
     * $_FILES
     * @var Array $_FILES
     */
    protected $upload;

    /**
     * File instance container
     */
    protected $fileContainer;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Path Resolver
     * @var PathResolver
     */
    protected PathResolver $pathresolver;

    /**
     * Default messgges
     * @var array
     */
    protected Array $messages = [
        UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload',
    ];

    /**
     * Validators
     * @var array
     */
    protected Array $validator;

    /**
     * @param Array $upload $_FILES
     * @param Array $server $_SERVER
     */
    public function __construct(Array $upload, Array $server)
    {
        $this->upload = isset($upload) ? $upload : null; 
        $this->server = $server;
    }
    public function setPathResolver(PathResolver $pathresolver)
    {
        $this->pathresolver = $pathresolver;
    }
    public function getPathResolver(): PathResolver
    {
        return $this->pathresolver;
    }

    /**
     * Add validators
     * @param array $validators
     * @return void
     */
    public function addValidator(Array $validators)
    {
        $this->validator = $validators;
    }

    public function getFilesystem(): Filesystem
    {
        return $this->filesystem;
    }
    public function setFilesystem(Filesystem $fs)
    {
        $this->filesystem = $fs;
    }
    public function getFile()
    {
        return $this->files;
    }

    public function processAll()
    {
        $this->files = [];
        $content_range = $this->getContentRange();
        $upload = $this->upload;

        if($upload && is_array($upload['tmp_name']))
        {
            foreach($upload['tmp_name'] as $index => $tmp_name)
            {
                if(empty($tmp_name))
                {
                continue;
                }
                $this->files[] =  $this->process(
                    $tmp_name,
                    $upload['name'][$index],
                    $upload['size'][$index],
                    $upload['type'][$index],
                    $upload['error'][$index],
                    $index,
                    $content_range
                );
            }
        } else if ($upload && $upload['tmp_name'])
        {
            $this->files[] = $this->process(
                $upload['tmp_name'],
                $upload['name'],
                $upload['size'],
                $upload['type'],
                $upload['error'],
                0,
                $content_range
            );
        } else if ($upload && $upload['error'] !== 0)
        {
            $file = new File($upload['name'], basename($upload['name']));
            $file->error = $this->getMessage($upload['error']);
            $file->errorCode = $upload['error'];
            $this->files[] = $file;
        }

        return [$this->files, $this->getNewHeaders($this->files, $content_range)];
    }

    public function validate($tmp_name, File $file, $error, $index)
    {
        if($error !== 0)
        {
            // PHP error
            $file->error = $this->getMessage($error);
            $file->errorCode = $error;

            return false;
        }
        if($tmp_name && $this->filesystem->isUploadedFile($tmp_name))
        {
            $current_size = $this->filesystem->getSize($tmp_name);
        } else
        {
            $current_size = $this->getContentLength();
        }

        foreach($this->validator as $validator)
        {
            if(!$validator->validate($file, $current_size))
            {
                return false;
            }
        }

        return true;
    }
    public function process($tmp_name, $name, $size, $type, $error, $index = 0, $content_range = NULL)
    {
        $this->fileContainer = $file = new File($tmp_name, $name);
        $file->name = $this->filterName($name);
        $file->size = $size;
        $completed = false;


        if($file->name)
        {
            if($this->validate($tmp_name, $file, $error, $index))
            {
                $upload_path = $this->pathresolver->getUploadPath();
                $file_path = $this->pathresolver->getUploadPath($file->name);
                $this->filesystem->moveUploadedFile($tmp_name, $file_path);
    
                $file_size = $this->filesystem->getSize($file_path);
    
                if($file->size === $file_size)
                {
                    $completed = true;
                } else
                {
                    $this->filesystem->delete($file_path);
                    $file->error = 'aborted';
                }
    
                $file = new File($file_path, $name);
                $file->completed = $completed;
                $file->size = $file_size;
            }
        }
        return $file;
    }

    public function filterName($name)
    {
        $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $name = str_replace(chr(0), "", $name);
        return preg_replace('/[\/\\\]/', "", $name);
    }

    /**
     * Get an error message.
     * @param int $code
     * @return string
     */
    public function getMessage(Int $code): String
    {
        return $this->messages[((string)$code)];
    }

    /**
     * Content-range header
     * @return array
     */
    protected function getContentRange()
    {
        return isset($this->server['HTTP_CONTENT_RANGE']) ?
        preg_split('/[^0-9]+/', $this->server['HTTP_CONTENT_RANGE']) : null;
    }
    /**
     * Content-length header
     * @return integer
     */
    protected function getContentLength()
    {
        return isset($this->server['CONTENT_LENGTH']) ? $this->server['CONTENT_LENGTH'] : null;
    }

    /**
     * Make headers for response
     * @var array $files
     * @var array $content_range
     * @return array
     */
    protected function getNewHeaders(Array $files, $content_range)
    {
        $headers = [
            'pragma' => 'no-cache',
            'cache-control' => 'no-store, no-cache, must-revalidate',
            'content-disposition' => 'inline; filename="files.json"',
            'x-content-type-options' => 'nosniff'
        ];

        if($content_range && is_object($files[0]) && $files[0]->size)
        {
            $headers['range'] = '0-' . ($files[0]->size);
        }

        return $headers;
    }
}