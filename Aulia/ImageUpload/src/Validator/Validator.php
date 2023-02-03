<?php
namespace Aulia\ImageUpload\Validator;

use Aulia\ImageUpload\File;

interface Validator
{
    /**
     * Overwrite the default error messages.
     * @param array $messages
     * @return void
     */
    public function setErrorMessages(Array $messages): void;

    /**
     * Validate file
     * @param File $file
     * @param null|int $current_size
     * @return bool
     */
    public function validate(File $file, ?int $current_size = null): bool;
}