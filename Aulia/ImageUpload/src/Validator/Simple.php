<?php
namespace Aulia\ImageUpload\Validator;

use Aulia\ImageUpload\File;
use Aulia\ImageUpload\Helper;
use Aulia\ImageUpload\Validator\Validator;

class Simple implements Validator
{
    const UPLOAD_ERR_BAD_TYPE = 0;
    const UPLOAD_ERR_TOO_LARGE = 1;

    /**
     * Max allowed size
     * @var string|int $maxsize
     */
    protected $maxsize;

    /**
     * Allowed file types
     * @var array $allowed_types
     */
    protected Array $allowed_types;

    /**
     * Error messages
     * @var array $messages
     */
    protected $messages = [
        self::UPLOAD_ERR_BAD_TYPE => 'File type not allowed',
        self::UPLOAD_ERR_TOO_LARGE => 'File size too large'
    ];

    public function __construct($maxsize, Array $allowed_types)
    {
        $this->setMaxSize($maxsize);
        $this->allowed_types = $allowed_types;
    }

    public function setMaxSize($max_size)
    {
        if(is_numeric($max_size))
        {
            $this->maxsize = $max_size;
        } else
        {
            $this->maxsize = Helper::humanReadableToBytes($max_size);
        }

        if($this->maxsize < 0 || $this->maxsize == null)
        {
            throw new \Exception('Invalid max size value');
        }
    }
    /**
     * @see Validatior
     */
    public function setErrorMessages(array $messages): void
    {
        foreach($messages as $key => $value)
        {
            $this->messages[$key] = $value;
        }
    }

    /**
     * @see Validator
     */
    public function validate(File $file, ?int $current_size = null): bool
    {
        if(!empty($this->allowed_types))
        {
            if(!in_array($file->getMimeType(), $this->allowed_types))
            {
                $file->error = $this->messages[self::UPLOAD_ERR_BAD_TYPE];

                return false;
            }
        }

        if($file->getSize() > $this->maxsize || $current_size > $this->maxsize)
        {
            $file->error = $this->messages[self::UPLOAD_ERR_TOO_LARGE];

            return false;
        }
        return true;
    }
}