<?php
/**
 * Configuration utility. This class is used for loading multiple configuration
 * files and accessing configuration values.
 * 
 * Config files are stored in 'application/config' and can be stored in 
 * nested subfolders.'
 * 
 * Example Usage
 *      Config::load('default');
 *      Config::get('default.upload.path');
 * 
 * @package     Base PHP Framework
 * @author      Chris Hayes <chris@chrishayes.ca>, <chayes@okd.com>
 * @copyright   (c) 2012-2013 Chris Hayes, OKD
 * @license     http://opensource.org/licenses/MIT
 */
class Config
{    
    /**
     * Store all of the configuration files we have loaded.
     * 
     * @var array 
     */
    public static $loadedFiles = array();

    /**
     * Load a configuration file and store it in an array.
     * 
     * @param   string  $fileName  Name of the config file to load.
     */
    public static function load($fileName)
    {
        // Load a single file
        if ( ! is_array($fileName)) {
            return self::loadFile($fileName);
        }

        // If we have an array load each file
        foreach ($fileName as $file) {
            self::loadFile($file);
        }
    }

    /**
     * Get a configuration item from an array using "dot" notation.
     * 
     * @param   string  $keys   Path using dot notation.
     * @param   mixed   $default   
     * @return  mixed 
     */
    public static function get($keys, $default = null)
    {
        $config = self::$loadedFiles;
        
        // If there are no parameters we send back the whole config array.
        if (is_null($keys)) return $config;
        
        return Arr::getFromString($keys, $config, $default);
    }

    private static function loadFile($fileName)
    {
        if (file_exists($path = APP_PATH.'config'.DS.str_replace('.', '/', $fileName).EXT)) {
            self::$loadedFiles = self::$loadedFiles + Arr::setFromString($fileName, require_once($path));
        }
    }
}