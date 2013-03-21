<?php
/**
 * Upload helper.
 * 
 * Remember to define your form with "enctype=multipart/form-data" or file
 * uploading will not work!
 * 
 * @package     Base PHP Framework
 * @author      Chris Hayes <chris@chrishayes.ca>, <chayes@okd.com>
 * @copyright   (c) 2012-2013 Chris Hayes, OKD
 * @license     http://opensource.org/licenses/MIT
 */
class Upload
{
    /**
     * Upload a file.
     * 
     * @param   array   $file
     * @param   string  $directory
     * @param   boolean $createDirectory
     * @return  mixed
     */
    public static function save($file, $directory, $createDirectory = false)
    {
        $directory = str_append($directory, '/');

        // Ignore corrupted uploads
        if ( ! isset($file['tmp_name']) || ! is_uploaded_file($file['tmp_name'])) {
            return false;
        }
        
        // Make sure the directory exists.
        if ( ! is_dir($directory)) {
            if ( ! $createDirectory) {
                return false;
            }

            mkdir($directory, 0777, true);
		}

        // Make sure the directory is writable.
        if ( ! is_writable(realpath($directory))) {
            return false;
        }

        // Produce a random number to prepend to image name for security reasons.
        $filename = uniqid() . $file['name'];

        // Remove spaces from the filename
        $filename = preg_replace('/\s+/u', '_', $filename);
        
        // Create our target image path with the prepended random number.
        $path = $directory . $filename;

        if (move_uploaded_file($file['tmp_name'], $path)) {
            return $filename;
        }

        return false;
    }
}