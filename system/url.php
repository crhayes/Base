<?php defined('SYS_PATH') or die('No direct script access.');
/**
 * URL helper class to make it easy to create URLs.
 * 
 * @package     ssMVC - Super Simple MVC
 * @author      Chris Hayes <chris at chrishayes.ca>
 * @copyright   (c) 2012 Chris Hayes
 */
class Url
{    
    /**
     * Create a URL to a route.
     * 
     * @param   string  $route
     * @return  string 
     */
    public static function toRoute($route)
    {
        return BASE_PATH.$route;
    }
}