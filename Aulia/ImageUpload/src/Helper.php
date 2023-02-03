<?php
namespace Aulia\ImageUpload;

class Helper
{
    /**
     * Convert human readable file size (10M, 10K, etc) into bytes
     * @param string $input
     * @return int
     */
    public static function humanReadableToBytes($input): int
    {
        $number = (int)$input;
        $units = [
            'b' => 1,
            'k' => 1024,
            'm' => 1048576,
            'g' => 1073741824
        ];
        $unit = strtolower(substr($input, -1));
        if(isset($units[$unit]))
        {
            return ($number * $units[$unit]);
        } else
        {
            return (null);
        }
    }
}